{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Your {{ plan_name }} Membership is Renewed!</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, thank you for renewing your <strong>{{ plan_name }}</strong> subscription on <strong>{{ site_title }}</strong>.
                            </p>

                            <table style="width: 100%; border: 1px solid #e1e1e1; border-radius: 8px; margin: 25px 0; padding: 20px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Active Plan:</strong></td>
                                    <td style="padding: 8px 0; font-weight: bold;">{{ plan_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>New Expiration Date:</strong></td>
                                    <td style="padding: 8px 0; color: #10b981; font-weight: bold;">{{ end_date }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Lifetime Savings So Far:</strong></td>
                                    <td style="padding: 8px 0; color: #10b981; font-weight: bold;">{{ total_savings }}</td>
                                </tr>
                            </table>

                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Your exclusive discounts and delivery privileges will continue without any interruption. Thank you for being a valued member!
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
