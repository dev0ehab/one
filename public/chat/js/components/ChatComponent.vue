<template>
  <div class="message" :status="status" :style="{ direction: dir }">
    <div class="container">
      <div class="messages" style="min-height: 70vh;width: 100%;">
        <div class="message-content">
          <div ref="chatContainer" class="msg-container" style="height: 70vh; overflow-y: auto;">
            <div v-if="allMsgs.length > 0">
              <div v-for="(message, index) in allMsgs" :key="index">
                <div :class="{ send: message.is_sender == 1, recive: message.is_sender == 0 }">
                  <div class="img-class">
                    <div class="senderClass">
                      <h6 class="bold name-user">{{ message.original_message.sender_name }}</h6>

                      <p v-if="message.original_message.type == 'text'">
                        {{ message.original_message.body }}
                      </p>

                      <a class="fancy-pdf"
                         v-else-if="message.original_message.type == 'file'"
                         download
                         :href="message.original_message.body">
                        {{ message.original_message.file_name ? message.original_message.file_name : message.original_message.name }}
                        <i class="fa fa-download mx-2"></i>
                        <img src="/site/assets/imgs/pdf_ic.png" alt="" class="pdf-chat">
                      </a>

                      <img class="appnded-img"
                           v-else-if="message.original_message.type == 'image'"
                           :src="message.original_message.body"
                           @click="openImgPreview(message.original_message.body)" />
                    </div>
                    <img :src="message.original_message.avatar" alt="" class="none-img">
                  </div>

                  <span class="time2">{{ getHumanDate(message.created_at) }}</span>
                </div>
              </div>
            </div>
            <img v-else src="/site/imgs/empty_data.svg" alt="" class="empty-chat">
          </div>

          <Transition name="slide-fade">
            <div @click.self="closeImgPreview" v-if="showImg" class="img-preview">
              <img :src="targetImg" />
              <i @click="closeImgPreview" class="close fa fa-times"></i>
            </div>
          </Transition>
        </div>

        <div v-if="status" class="message-send">
          <form id="form" action="" ref="chatForm" class="d-flex align-items-center">
            <div class="btn-msg-bar">
              <button class="btn site-btn site-btn-update" id="myBtn" type="submit" @click.prevent="addMessage">
                إرسال
              </button>

              <div class="msg-input">
                <span v-show="fileChosen != ''" class="delete-file cp" @click="clearInput">
                  <i class="fa fa-times small"></i>
                </span>
                <textarea cols="30" rows="2" class="form-control" placeholder="اكتب الرسالة"
                          v-model="message"
                          @keyup.enter.exact="addMessage"
                          @keydown.enter.shift.exact="newline"
                          :readonly="fileChosen != ''">
                </textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// Use the global timeago that's loaded in app.js

module.exports = {
  data() {
    return {
      origin: window.location.origin,
      message: "",
      fileType: null,
      typing: false,
      timeout: "",
      socket: "",
      allMsgs: [],
      otherMember: [],
      fileChosen: "",
      otherRoomUsers: [],
      room_id: null,
      avatar: "",
      sender_name: "",
      showImg: false,
      targetImg: "",
      dir: "ltr",
      loading: true,
    };
  },
  props: ["messages", "room", "members", "type", "status"],
  sockets: {
    sendMessageRes: function (data) {
      const dateIso = new Date().toISOString();

      if (!(data.senderable_type === "App\\Models\\Admin" && data.sender_id == USER_ID)) {
        this.allMsgs.push({
          created_at: dateIso,
          is_sender: 0,
          original_message: {
            body: data.body,
            type: data.type,
            file_name: data.file_name,
            avatar: data.avatar,
            sender_name: data.sender_name ?? data.name,
          },
        });
        this.scrollToBottom();
      }

      this.$socket.emit("seenMessages", {
        room_id: data.room_id,
      });
    },
  },
  methods: {
    getHumanDate(dateInput) {
      if (!dateInput) return "";

      let date;
      if (dateInput instanceof Date) {
        date = dateInput;
      } else if (typeof dateInput === "string") {
        // Laravel format "YYYY-MM-DD HH:mm:ss"
        if (dateInput.includes("T") || dateInput.endsWith("Z")) {
          date = new Date(dateInput);
        } else {
          date = new Date(dateInput.replace(" ", "T") + "Z");
        }
      } else {
        date = new Date(dateInput);
      }

      // Use global timeago
      if (window.timeago && window.timeago.format) {
        return window.timeago.format(date, window.LANG ?? "ar");
      } else {
        // Fallback
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (window.LANG === 'ar') {
          if (minutes < 1) return 'الآن';
          if (minutes < 60) return `منذ ${minutes} دقيقة`;
          if (hours < 24) return `منذ ${hours} ساعة`;
          return `منذ ${days} يوم`;
        } else {
          if (minutes < 1) return 'now';
          if (minutes < 60) return `${minutes} minutes ago`;
          if (hours < 24) return `${hours} hours ago`;
          return `${days} days ago`;
        }
      }
    },

    clearInput() {
      this.fileChosen = "";
      this.message = "";
      this.$refs.chatForm.reset();
    },

    newline() {
      this.value = `${this.value}\n`;
    },

    handleFileUpload(type) {
      let fileInput;
      if (type === "image") fileInput = this.$refs.imageFile;
      else if (type === "pdf") fileInput = this.$refs.pdfFile;

      const file = fileInput.files[0];
      if (file) {
        this.fileChosen = file;
        this.message = file.name;
        this.fileType = file.type.split("/")[0];
      }
    },

    addMessage() {
      if (this.fileChosen != "") {
        if (this.fileChosen.size > 10000000) {
          this.fileChosen = "";
          this.message = "";
          return false;
        }
        let formData = new FormData();
        formData.append("file", this.fileChosen);
        formData.append("room_id", this.room.id);

        this.uploadFile(formData);
        this.clearInput();
      } else if (this.message.trim() == "") {
        return false;
      } else {
        this.sendMessage(this.message, "text");
      }
    },

    sendMessage($msg, $type = "text", $file_url = null, file_name = null) {
      this.$socket.emit("sendMessage", {
        sender_id: window.USER_ID,
        sender_type: window.USER_TYPE,
        order_num: window.ORDER_NUM,
        sender_name: this.sender_name,
        avatar: this.avatar,
        receiver_id: this.otherMember.memberable.id,
        receiver_type: this.otherMember.memberable_type.slice(11),
        room_id: this.room.id,
        type: $type,
        chatType: this.type,
        body: $msg,
        file_name: file_name,
      });

      let body = $msg;
      if ($file_url != null) body = $file_url;

      const dateIso = new Date().toISOString();

      let message = {
        room_id: this.room.id,
        created_at: dateIso,
        is_sender: 1,
        original_message: {
          body: body,
          type: $type,
          file_name: file_name,
          avatar: this.avatar,
          sender_name: this.sender_name,
        },
      };
      this.$emit("newinnermessage", message);
      this.allMsgs.push(message);
      this.scrollToBottom();

      this.message = "";
    },

    openImgPreview(src) {
      this.showImg = true;
      this.targetImg = src;
    },

    closeImgPreview() {
      this.showImg = false;
    },

    scrollToBottom() {
      this.$nextTick(() => {
        const chatContainer = this.$refs.chatContainer;
        if (chatContainer) {
          chatContainer.scrollTop = chatContainer.scrollHeight;
        }
      });
    },
  },

  created() {
    this.avatar = window.USER_AVATAR;
    this.sender_name = window.USER_NAME;
    this.allMsgs = this.messages.reverse();

    if (this.members) {
      this.otherMember = this.members[0];
    } else {
      console.error("there is no members");
    }

    this.$socket.emit("enterChat", {
      user_id: window.USER_ID,
      user_type: window.USER_TYPE,
      room_id: this.room.id,
    });
  },

  mounted() {
    setTimeout(() => {
      this.loading = false;
      this.scrollToBottom();
    }, 500);
  },
};
</script>
