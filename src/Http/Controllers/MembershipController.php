<?php

namespace Botble\Membership\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Membership\Forms\MembershipForm;
use Botble\Membership\Http\Requests\MembershipRequest;
use Botble\Membership\Repositories\Interfaces\MembershipInterface;
use Botble\Membership\Tables\MembershipTable;
use Exception;
use Illuminate\Http\Request;

class MembershipController extends BaseController
{
    /**
     * @var MembershipInterface
     */
    protected $membershipRepository;

    public function __construct(MembershipInterface $membershipRepository)
    {
        $this->membershipRepository = $membershipRepository;
    }

    public function index(MembershipTable $table)
    {
        page_title()->setTitle(trans('plugins/membership::membership.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/membership::membership.create'));

        return $formBuilder->create(MembershipForm::class)->renderForm();
    }

    public function store(MembershipRequest $request, BaseHttpResponse $response)
    {
        $membership = $this->membershipRepository->createOrUpdate($request->input());

        // Sync customer status
        $customer = $membership->user;
        if ($customer) {
            $customer->membership_plan = $membership->plan_id;
            $customer->membership_status = $membership->status;
            $customer->save();
        }

        event(new CreatedContentEvent(MEMBERSHIP_MODULE_SCREEN_NAME, $request, $membership));

        return $response
            ->setPreviousUrl(route('memberships.index'))
            ->setNextUrl(route('memberships.edit', $membership->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder)
    {
        $membership = $this->membershipRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/membership::membership.edit'));

        return $formBuilder->create(MembershipForm::class, ['model' => $membership])->renderForm();
    }

    public function update(int $id, MembershipRequest $request, BaseHttpResponse $response)
    {
        $membership = $this->membershipRepository->findOrFail($id);

        $membership->fill($request->input());

        $this->membershipRepository->createOrUpdate($membership);

        // Sync customer status
        $customer = $membership->user;
        if ($customer) {
            $customer->membership_plan = $membership->plan_id;
            $customer->membership_status = $membership->status;
            $customer->save();
        }

        event(new UpdatedContentEvent(MEMBERSHIP_MODULE_SCREEN_NAME, $request, $membership));

        return $response
            ->setPreviousUrl(route('memberships.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $membership = $this->membershipRepository->findOrFail($id);
            $this->membershipRepository->delete($membership);

            event(new DeletedContentEvent(MEMBERSHIP_MODULE_SCREEN_NAME, $request, $membership));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $membership = $this->membershipRepository->findOrFail($id);
            $this->membershipRepository->delete($membership);
            event(new DeletedContentEvent(MEMBERSHIP_MODULE_SCREEN_NAME, $request, $membership));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
