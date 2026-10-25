<?php

namespace Botble\Membership\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static PlanStatusEnum ACTIVE()
 * @method static PlanStatusEnum INACTIVE()
 */
class PlanStatusEnum extends Enum
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';

    /**
     * @var string
     */
    public static $langPath = 'plugins/membership::plans.statuses';

    /**
     * @return string
     */
    public function toHtml()
    {
        switch ($this->value) {
            case self::ACTIVE:
                return Html::tag('span', self::ACTIVE()->label(), ['class' => 'label-success status-label'])
                    ->toHtml();
            case self::INACTIVE:
                return Html::tag('span', self::INACTIVE()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            default:
                return parent::toHtml();
        }
    }

    /**
     * @return array
     */
    public static function labels(): array
    {
        return [
            self::ACTIVE   => trans('plugins/membership::plans.statuses.active'),
            self::INACTIVE => trans('plugins/membership::plans.statuses.inactive'),
        ];
    }
}
