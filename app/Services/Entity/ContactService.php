<?php

namespace App\Services\Entity;

use App\Models\Contact;
use Illuminate\Support\Facades\Log;

class ContactService extends BaseService
{
    public function __construct(Contact $contact)
    {
        $this->model = $contact;
    }

    public function index()
    {
        if (request()->ajax()) {
            $query = $this->model::with(['contactable'])->search(request()->searchArray);
            $contacts = $query->latest()->paginate(30);
            $tableViewPath = view()->exists('admin.contacts.table')
                ? 'admin.contacts.table'
                : 'admin.contact.table';

            $html = view($tableViewPath, get_defined_vars())->render();
            return response()->json(['html' => $html]);
        }

        $indexViewPath = view()->exists('admin.contacts.index')
            ? 'admin.contacts.index'
            : 'admin.contact.index';

        return view($indexViewPath);
    }

    public function show($id)
    {
        $contact = $this->model::with(['contactable'])->findOrFail($id);

        return view('admin.contacts.show', ['contact' => $contact]);
    }

    public function reply($request, $id)
    {
        $contact = $this->model::findOrFail($id);

        if (is_null($contact->contactable_type)) {
            return response()->json([
                'key' => 'error',
                'msg' => __('admin.cannot_reply_to_this_contact')
            ], 422);
        }

        $reply = $request->input('reply') ?? $request->reply;

        if (empty($reply)) {
            return response()->json([
                'key' => 'error',
                'msg' => __('admin.this_field_is_required')
            ], 422);
        }

        $contact->update(['reply' => $reply]);
        $email = $contact->email ?? $contact->contactable?->email;
        if (!empty($email)) {
            try {
                $contact->sendReplayEmail($reply);
            } catch (\Exception $e) {
                Log::error('Failed to send reply email: ' . $e->getMessage());
            }
        }

        return response()->json(['url' => route('admin.all_contacts')]);
    }
}
