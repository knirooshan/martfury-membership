<?php

namespace Botble\Membership\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Membership\Forms\PlanForm;
use Botble\Membership\Http\Requests\PlanRequest;
use Botble\Membership\Repositories\Interfaces\PlanInterface;
use Botble\Membership\Tables\PlanTable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends BaseController
{
    /**
     * @var PlanInterface
     */
    protected $planRepository;

    public function __construct(PlanInterface $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    public function index(PlanTable $table)
    {
        page_title()->setTitle(trans('plugins/membership::plans.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/membership::plans.create'));

        return $formBuilder->create(PlanForm::class)->renderForm();
    }

    public function store(PlanRequest $request, BaseHttpResponse $response)
    {
        $input = $request->input();
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($input['name']);
        }

        $plan = $this->planRepository->createOrUpdate($input);

        event(new CreatedContentEvent(PLAN_MODULE_SCREEN_NAME, $request, $plan));

        return $response
            ->setPreviousUrl(route('plans.index'))
            ->setNextUrl(route('plans.edit', $plan->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(int $id, FormBuilder $formBuilder)
    {
        $plan = $this->planRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/membership::plans.edit', ['name' => $plan->name]));

        return $formBuilder->create(PlanForm::class, ['model' => $plan])->renderForm();
    }

    public function update(int $id, PlanRequest $request, BaseHttpResponse $response)
    {
        $plan = $this->planRepository->findOrFail($id);

        $input = $request->input();
        if (empty($input['slug'])) {
            $input['slug'] = Str::slug($input['name']);
        }

        $plan->fill($input);

        $this->planRepository->createOrUpdate($plan);

        event(new UpdatedContentEvent(PLAN_MODULE_SCREEN_NAME, $request, $plan));

        return $response
            ->setPreviousUrl(route('plans.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(int $id, Request $request, BaseHttpResponse $response)
    {
        try {
            $plan = $this->planRepository->findOrFail($id);
            $this->planRepository->delete($plan);

            event(new DeletedContentEvent(PLAN_MODULE_SCREEN_NAME, $request, $plan));

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
            $plan = $this->planRepository->findOrFail($id);
            $this->planRepository->delete($plan);
            event(new DeletedContentEvent(PLAN_MODULE_SCREEN_NAME, $request, $plan));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
