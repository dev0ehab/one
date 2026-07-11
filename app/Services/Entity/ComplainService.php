<?php

namespace App\Services\Entity;

use App\Enums\ComplainStatusEnum;
use App\Enums\NotificationTypeEnum;
use App\Services\Notification\UserNotifier;
use App\Http\Filters\ComplainFilter;
use App\Models\Client;
use App\Models\Complain;
use App\Models\ComplainImage;
use App\Traits\ReportTrait;
use Illuminate\Support\Facades\DB;

class ComplainService extends BaseService
{
    use ReportTrait;

    public function __construct(Complain $complain, ComplainFilter $filter)
    {
        parent::__construct($complain);
        $this->filter = $filter;
    }

    public function indexApi()
    {
        $data = $this->model::filter($this->filter)
            ->with(['complainable', 'images'])
            ->latest()
            ->when((!request()->isExtended), function ($query) {
                return $query->paginate(request()->paginate);
            });

        return $this->apiCollectionResponse($data);
    }

    public function showApi($id)
    {
        $data = $this->model::with(['complainable', 'images'])->findOrFail($id);
        return $this->apiResourceResponse($data);
    }

    public function index($client_id = null)
    {
        if (request()->ajax()) {
            $complains = $this->model::with(['complainable', 'images'])
                ->when($client_id, function ($query) use ($client_id) {
                    $query->where('complainable_id', $client_id)
                        ->where('complainable_type', Client::class);
                })
                ->search(request()->searchArray)
                ->latest()
                ->paginate(30);

            $tableViewPath = view()->exists('admin.complains.table')
                ? 'admin.complains.table'
                : 'admin.complain.table';

            $html = view($tableViewPath, get_defined_vars())->render();
            return response()->json(['html' => $html]);
        }

        $indexViewPath = view()->exists('admin.complains.index')
            ? 'admin.complains.index'
            : 'admin.complain.index';

        return view($indexViewPath);
    }

    public function show($id)
    {
        $complain = $this->model::with(['complainable', 'images'])->findOrFail($id);

        $viewPath = view()->exists('admin.complains.show')
            ? 'admin.complains.show'
            : 'admin.complain.show';

        return view($viewPath, compact('complain'));
    }


    public function store($request)
    {
        DB::beginTransaction();

        try {
            $data     = method_exists($request, 'validated') ? $request->validated() : $request->all();
            $complain = auth()->user()->complains()->create($data);

            // Handle images if provided
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    ComplainImage::create([
                        'complain_id' => $complain->id,
                        'image' => $image,
                    ]);
                }
            }

            DB::commit();

            return ['status'  => 'success', 'message' => __('apis.success')];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Change status to processing (pending)
     */
    public function processing($request, $id)
    {
        $complain = Complain::findOrFail($id);

        if ($complain->status !== ComplainStatusEnum::NEW) {
            return response()->json(['key' => 'error', 'msg' => __('admin.invalid_status')], 422);
        }

        $complain->update([
            'status' => ComplainStatusEnum::PENDING,
        ]);

        return response()->json(['url' => route('admin.complains.index')]);
    }

    /**
     * Change status to completed (finish)
     */
    public function completed($request, $id)
    {
        $data = method_exists($request, 'validated') ? $request->validated() : $request->all();
        $complain = Complain::findOrFail($id);

        if ($complain->status !== ComplainStatusEnum::PENDING) {
            return response()->json(['key' => 'error', 'msg' => __('admin.invalid_status')], 422);
        }

        $complain->update([
            'status' => ComplainStatusEnum::FINISH,
            'reply'  => $data['reply'],
        ]);

        $complain->load('complainable');

        if ($complain->complainable) {
            UserNotifier::send(
                $complain->complainable,
                NotificationTypeEnum::ComplaintReplied,
                $complain,
                $complain->id
            );
        }

        return response()->json(['url' => route('admin.complains.index')]);
    }

    public function destroy($id)
    {
        $complain = $this->model::with('images')->findOrFail($id);

        // Delete associated images
        foreach ($complain->images as $image) {
            $image->delete();
        }

        // Delete the complain
        $complain->delete();

        if (!$this->isApiRequest()) {
            ReportTrait::addToLog('delete_complain');
        }

        return ['status' => 'success'];
    }
}
