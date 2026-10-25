{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Congratulations on Upgrading to {{ plan_name }}!</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, you have successfully upgraded your tier on <strong>{{ site_title }}</strong>!
                            </p>

                            <table style="width: 100%; border: 1px solid #e1e1e1; border-radius: 8px; margin: 25px 0; padding: 20px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="padding: 8px 0;"><strong>New Plan:</strong></td>
                                    <td style="padding: 8px 0; color: #10b981; font-weight: bold;">{{ plan_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Delivery Discount:</strong></td>
                                    <td style="padding: 8px 0; font-weight: bold;">{{ delivery_discount_percentage }}% Off</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Valid Until:</strong></td>
                                    <td style="padding: 8px 0;">{{ end_date }}</td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Start enjoying higher discounts and lower delivery fees across our entire store right now!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
