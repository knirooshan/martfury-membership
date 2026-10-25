{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #b91c1c; font-weight: 700; margin-bottom: 15px;">Final Notice: Your {{ plan_name }} Membership Expires Tomorrow!</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, your <strong>{{ plan_name }}</strong> benefits on <strong>{{ site_title }}</strong> will expire in less than 24 hours on <strong>{{ end_date }}</strong>.
                            </p>

                            <div style="background-color: #fef2f2; border: 1px solid #f87171; border-radius: 8px; padding: 20px; margin: 25px 0;">
                                <strong style="color: #991b1b; font-size: 15px; display: block; margin-bottom: 6px;">After tomorrow:</strong>
                                <ul style="color: #7f1d1d; font-size: 14px; margin: 0; padding-left: 20px; line-height: 22px;">
                                    <li>Product prices will revert to regular retail rates.</li>
                                    <li>Delivery discounts will be discontinued on future orders.</li>
                                </ul>
                            </div>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ renewal_url }}" style="background-color: #dc2626; color: #fff; font-weight: bold; text-decoration: none; padding: 14px 36px; border-radius: 6px; display: inline-block; font-size: 15px;">
                                    Extend My Benefits Today
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
