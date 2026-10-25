<?php

namespace Botble\Membership\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Membership\Console\Commands\CheckMembershipExpirationCommand;
use Botble\Membership\Models\Membership;
use Botble\Membership\Models\MembershipProductPrice;
use Botble\Membership\Models\Plan;
use Botble\Membership\Repositories\Caches\MembershipCacheDecorator;
use Botble\Membership\Repositories\Caches\MembershipProductPriceCacheDecorator;
use Botble\Membership\Repositories\Caches\PlanCacheDecorator;
use Botble\Membership\Repositories\Eloquent\MembershipProductPriceRepository;
use Botble\Membership\Repositories\Eloquent\MembershipRepository;
use Botble\Membership\Repositories\Eloquent\PlanRepository;
use Botble\Membership\Repositories\Interfaces\MembershipInterface;
use Botble\Membership\Repositories\Interfaces\MembershipProductPriceInterface;
use Botble\Membership\Repositories\Interfaces\PlanInterface;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class MembershipServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(PlanInterface::class, function () {
            return new PlanCacheDecorator(new PlanRepository(new Plan));
        });

        $this->app->bind(MembershipInterface::class, function () {
            return new MembershipCacheDecorator(new MembershipRepository(new Membership));
        });

        $this->app->bind(MembershipProductPriceInterface::class, function () {
            return new MembershipProductPriceCacheDecorator(new MembershipProductPriceRepository(new MembershipProductPrice));
        });
    }

    public function boot()
    {
        $this
            ->setNamespace('plugins/membership')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general', 'email'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes(['web', 'front'])
            ->loadAndPublishViews();

        if ($this->app->runningInConsole()) {
            $this->commands([
                CheckMembershipExpirationCommand::class,
            ]);
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
                ->registerItem([
                    'id'          => 'cms-plugins-membership',
                    'priority'    => 15,
                    'parent_id'   => null,
                    'name'        => 'plugins/membership::membership.name',
                    'icon'        => 'far fa-id-card',
                    'url'         => route('memberships.index'),
                    'permissions' => ['memberships.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-membership-list',
                    'priority'    => 1,
                    'parent_id'   => 'cms-plugins-membership',
                    'name'        => 'plugins/membership::membership.all',
                    'icon'        => null,
                    'url'         => route('memberships.index'),
                    'permissions' => ['memberships.index'],
                ])
                ->registerItem([
                    'id'          => 'cms-plugins-membership-plans',
                    'priority'    => 2,
                    'parent_id'   => 'cms-plugins-membership',
                    'name'        => 'plugins/membership::plans.name',
                    'icon'        => null,
                    'url'         => route('plans.index'),
                    'permissions' => ['plans.index'],
                ]);
        });

        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
    }
}
