{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Complete Payment for Your {{ plan_name }} Membership</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, thank you for subscribing to <strong>{{ plan_name }}</strong> on <strong>{{ site_title }}</strong>!
                            </p>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Your order reference is <strong>#{{ order_id }}</strong> with a total amount of <strong>{{ plan_amount }}</strong>.
                            </p>

                            <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 18px; margin: 25px 0;">
                                <strong style="color: #b45309; font-size: 15px; display: block; margin-bottom: 6px;">Next Step: Complete Payment</strong>
                                <p style="color: #92400e; font-size: 14px; margin: 0; line-height: 22px;">
                                    Please transfer the total amount using your selected offline payment method and provide your order reference #{{ order_id }}. Once verified by our team, your membership benefits will be instantly activated.
                                </p>
                            </div>

                            <p style="font-size: 14px; color: #777;">
                                Cheers,<br/>
                                The {{ site_title }} Team
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
