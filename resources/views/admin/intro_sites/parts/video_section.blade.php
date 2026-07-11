{{-- Video Section Tab --}}
<div class="tab-pane fade" id="video_section" role="tabpanel" aria-labelledby="video_section-tab">
    
    {{-- Video Upload Area --}}
    <div class="video-upload-container">
        <div class="video-upload-area" id="videoUploadArea">
            <div class="upload-content">
                <div class="upload-icon">
                    <i class="feather icon-video"></i>
                </div>
                {{-- <h4 class="upload-title">{{ __('admin.upload_video') }}</h4>
                <p class="upload-description">{{ __('admin.video_upload_description') }}</p> --}}
                <div class="upload-button">
                    <label for="videoFile" class="btn btn-primary btn-lg">
                        <i class="feather icon-upload mr-2"></i>
                        {{ __('admin.choose_video') }}
                    </label>
                    <input type="file" accept="video/*" name="video" id="videoFile" class="d-none">
                </div>
                <div class="upload-info">
                    <small class="text-muted">{{ __('admin.supported_formats') }}: MP4, AVI, MOV, WMV</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Video Preview Area --}}
    @if($intro_images['video'])
        <div class="video-preview-container">
            <div class="preview-header">
                <h5 class="preview-title">
                    <i class="feather icon-eye mr-2"></i>
                    {{ __('admin.video_preview') }}
                </h5>
                <button type="button" class="btn btn-sm btn-outline-danger remove-video">
                    <i class="feather icon-trash-2"></i>
                    {{ __('admin.remove_video') }}
                </button>
            </div>
            <div class="video-wrapper">
                <video id="videoPreview" src="{{ $intro_images['video'] }}" controls preload="metadata">
                    <div class="video-error">
                        <i class="feather icon-alert-circle"></i>
                        <p>{{ __('admin.video_not_supported') }}</p>
                    </div>
                </video>
            </div>
        </div>
    @endif

    <style>
        .video-upload-container {
            margin-bottom: 2rem;
        }
        
        .video-upload-area {
            border: 3px dashed #e0e6ed;
            border-radius: 12px;
            padding: 3rem 2rem;
            text-align: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .video-upload-area {
                border-color: #495057;
                background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            }
        }
        
        .video-upload-area:hover {
            border-color: #007bff;
            background: linear-gradient(135deg, #e3f2fd 0%, #f8f9fa 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.15);
        }
        
        @media (prefers-color-scheme: dark) {
            .video-upload-area:hover {
                border-color: #007bff;
                background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
                box-shadow: 0 8px 25px rgba(0, 123, 255, 0.3);
            }
        }
        
        .video-upload-area.dragover {
            border-color: #28a745;
            background: linear-gradient(135deg, #e8f5e8 0%, #f8f9fa 100%);
        }
        
        @media (prefers-color-scheme: dark) {
            .video-upload-area.dragover {
                border-color: #10b981;
                background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            }
        }
        
        .upload-content {
            position: relative;
            z-index: 2;
        }
        
        .upload-icon {
            font-size: 4rem;
            color: #007bff;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .upload-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        @media (prefers-color-scheme: dark) {
            .upload-title {
                color: #e2e8f0;
            }
        }
        
        .upload-description {
            color: #6c757d;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        @media (prefers-color-scheme: dark) {
            .upload-description {
                color: #a0aec0;
            }
        }
        
        .upload-button .btn {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .upload-button .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }
        
        .upload-info {
            margin-top: 1rem;
        }
        
        .upload-info small {
            color: #6c757d;
        }
        
        @media (prefers-color-scheme: dark) {
            .upload-info small {
                color: #a0aec0;
            }
        }
        
        .video-preview-container {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 2rem;
        }
        
        @media (prefers-color-scheme: dark) {
            .video-preview-container {
                background: #2d3748;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            }
        }
        
        .preview-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .preview-title {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .remove-video {
            border-color: rgba(255, 255, 255, 0.3);
            color: white;
        }
        
        .remove-video:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }
        
        .video-wrapper {
            padding: 1.5rem;
            background: #f8f9fa;
        }
        
        @media (prefers-color-scheme: dark) {
            .video-wrapper {
                background: #1a202c;
            }
        }
        
        .video-wrapper video {
            width: 100%;
            max-height: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .video-error {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        
        @media (prefers-color-scheme: dark) {
            .video-error {
                color: #a0aec0;
            }
        }
        
        .video-error i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dc3545;
        }
        
        @media (prefers-color-scheme: dark) {
            .video-error i {
                color: #f56565;
            }
        }
        
        @media (max-width: 768px) {
            .video-upload-area {
                padding: 2rem 1rem;
            }
            
            .upload-icon {
                font-size: 3rem;
            }
            
            .preview-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('videoUploadArea');
            const videoFile = document.getElementById('videoFile');
            const videoPreview = document.getElementById('videoPreview');
            
            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    videoFile.files = files;
                    handleVideoUpload(files[0]);
                }
            });
            
            // File input change
            videoFile.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleVideoUpload(e.target.files[0]);
                }
            });
            
            // Click to upload - only on the upload area, not the button
            uploadArea.addEventListener('click', function(e) {
                // Only trigger if clicking on the upload area itself, not on buttons or inputs
                if (e.target === uploadArea || e.target.classList.contains('upload-content') || 
                    e.target.classList.contains('upload-icon') || e.target.classList.contains('upload-title') || 
                    e.target.classList.contains('upload-description') || e.target.classList.contains('upload-info')) {
                    videoFile.click();
                }
            });
            
            function handleVideoUpload(file) {
                if (file.type.startsWith('video/')) {
                    const url = URL.createObjectURL(file);
                    if (videoPreview) {
                        videoPreview.src = url;
                    }
                    
                    // Show preview container if hidden
                    const previewContainer = document.querySelector('.video-preview-container');
                    if (!previewContainer) {
                        // Create preview container dynamically
                        createVideoPreview(url);
                    }
                } else {
                    alert('{{ __("admin.please_select_video") }}');
                }
            }
            
            function createVideoPreview(url) {
                const container = document.createElement('div');
                container.className = 'video-preview-container';
                container.innerHTML = `
                    <div class="preview-header">
                        <h5 class="preview-title">
                            <i class="feather icon-eye mr-2"></i>
                            {{ __('admin.video_preview') }}
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-video">
                            <i class="feather icon-trash-2"></i>
                            {{ __('admin.remove_video') }}
                        </button>
                    </div>
                    <div class="video-wrapper">
                        <video id="videoPreview" src="${url}" controls preload="metadata">
                            <div class="video-error">
                                <i class="feather icon-alert-circle"></i>
                                <p>{{ __('admin.video_not_supported') }}</p>
                            </div>
                        </video>
                    </div>
                `;
                
                uploadArea.parentNode.appendChild(container);
            }
            
            // Remove video functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-video')) {
                    if (confirm('{{ __("admin.confirm_remove_video") }}')) {
                        videoFile.value = '';
                        const previewContainer = document.querySelector('.video-preview-container');
                        if (previewContainer) {
                            previewContainer.remove();
                        }
                    }
                }
            });
        });
    </script>
</div>
