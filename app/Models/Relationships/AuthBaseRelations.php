<?php

namespace App\Models\Relationships;

use App\Models\AuthOtp;
use App\Models\Bank;
use App\Models\Complain;
use App\Models\Contact;
use App\Models\ContactReply;
use App\Models\Device;
use App\Models\Room;
use App\Models\RoomMember;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait AuthBaseRelations
{
    /**
     * Get all of the user's otps.
     */
    public function otps(): MorphMany
    {
        return $this->morphMany(AuthOtp::class, 'otpable');
    }

    /**
     * Get all of the user's devices.
     */
    public function devices(): MorphMany
    {
        return $this->morphMany(Device::class, 'devicable');
    }

    /**
     * Get user's current device.
     */
    public function currentDevice(): MorphOne
    {
        return $this->morphOne(Device::class, 'devicable')->where('is_current', true);
    }

    /**
     * Get the the user's wallet.
     */
    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable')->latest();
    }

    /**
     * Get all of the user's bank accounts.
     */
    public function banks(): MorphMany
    {
        return $this->morphMany(Bank::class, 'bankable');
    }

    /**
     * Get the merchant's bank account.
     * @return MorphOne
     */
    public function bankAccount(): MorphOne
    {
        return $this->morphOne(Bank::class, 'bankable');
    }

    /**
     * Get all of the rooms for the user
     *
     * @return MorphMany
     */
    public function rooms(): MorphMany
    {
        return $this->morphMany(RoomMember::class, 'memberable');
    }

    /**
     * Get all of the own rooms for the user
     *
     * @return MorphMany
     */
    public function ownRooms(): MorphMany
    {
        return $this->morphMany(Room::class, 'createable');
    }

    /**
     * Get all of the joined room memberships for the user (via room_members table)
     *
     * @return MorphMany
     */
    public function joinedRooms(): MorphMany
    {
        return $this->morphMany(RoomMember::class, 'memberable');
    }

    /**
     * Get all of the contacts for the user
     *
     * @return MorphMany
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Get all of the complains for the user
     *
     * @return MorphMany
     */
    public function complains(): MorphMany
    {
        return $this->morphMany(Complain::class, 'complainable');
    }

    /**
     * Get the replies for the user.
     * @return MorphMany
     */
    public function replies(): MorphMany
    {
        return $this->morphMany(ContactReply::class, 'answerable');
    }
}
