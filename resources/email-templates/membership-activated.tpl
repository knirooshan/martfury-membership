{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Welcome to {{ plan_name }}, {{ customer_name }}!</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Your membership on <strong>{{ site_title }}</strong> is now active. You have officially unlocked exclusive member-only pricing and special privileges.
                            </p>

                            <table style="width: 100%; border: 1px solid #e1e1e1; border-radius: 8px; margin: 25px 0; padding: 20px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Plan Tier:</strong></td>
                                    <td style="padding: 8px 0; color: #10b981; font-weight: bold;">{{ plan_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Active From:</strong></td>
                                    <td style="padding: 8px 0;">{{ start_date }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Valid Until:</strong></td>
                                    <td style="padding: 8px 0;">{{ end_date }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Delivery Discount:</strong></td>
                                    <td style="padding: 8px 0;">{{ delivery_discount_percentage }}% Off Shipping</td>
                                </tr>
                            </table>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ dashboard_url }}" style="background-color: #fcb800; color: #222; font-weight: bold; text-decoration: none; padding: 14px 32px; border-radius: 6px; display: inline-block; font-size: 15px;">
                                    View My Membership Portal
                                </a>
                            </p>

                            <p style="font-size: 14px; color: #777;">
                                If you have any questions or need assistance with your perks, feel free to reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
