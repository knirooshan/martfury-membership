<?php

namespace Botble\Membership\Enums;

use Botble\Base\Supports\Enum;
use Html;

/**
 * @method static MembershipStatusEnum ACTIVE()
 * @method static MembershipStatusEnum INACTIVE()
 * @method static MembershipStatusEnum EXPIRED()
 * @method static MembershipStatusEnum PENDING_PAYMENT()
 * @method static MembershipStatusEnum CANCELLED()
 */
class MembershipStatusEnum extends Enum
{
    public const ACTIVE = 'active';
    public const INACTIVE = 'inactive';
    public const EXPIRED = 'expired';
    public const PENDING_PAYMENT = 'pending_payment';
    public const CANCELLED = 'cancelled';

    /**
     * @var string
     */
    public static $langPath = 'plugins/membership::membership.statuses';

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
            case self::EXPIRED:
                return Html::tag('span', self::EXPIRED()->label(), ['class' => 'label-danger status-label'])
                    ->toHtml();
            case self::PENDING_PAYMENT:
                return Html::tag('span', self::PENDING_PAYMENT()->label(), ['class' => 'label-warning status-label'])
                    ->toHtml();
            case self::CANCELLED:
                return Html::tag('span', self::CANCELLED()->label(), ['class' => 'label-default status-label'])
                    ->toHtml();
            default:
                return Html::tag('span', ucfirst($this->value), ['class' => 'label-default status-label'])
                    ->toHtml();
        }
    }

    /**
     * @return array
     */
    public static function labels(): array
    {
        return [
            self::ACTIVE          => trans('plugins/membership::membership.statuses.active'),
            self::INACTIVE        => trans('plugins/membership::membership.statuses.inactive'),
            self::EXPIRED         => trans('plugins/membership::membership.statuses.expired'),
            self::PENDING_PAYMENT => trans('plugins/membership::membership.statuses.pending_payment'),
            self::CANCELLED       => trans('plugins/membership::membership.statuses.cancelled'),
        ];
    }
}
