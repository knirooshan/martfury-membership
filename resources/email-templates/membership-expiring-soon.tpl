{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Your {{ plan_name }} Membership Expires in {{ days_remaining }} Days</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, this is a friendly reminder that your <strong>{{ plan_name }}</strong> membership on <strong>{{ site_title }}</strong> will expire on <strong>{{ end_date }}</strong>.
                            </p>

                            <div style="background-color: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; padding: 20px; margin: 25px 0; text-align: center;">
                                <span style="color: #065f46; font-size: 14px; font-weight: 600; display: block;">You Saved With Us This Year:</span>
                                <span style="color: #047857; font-size: 28px; font-weight: 800; display: block; margin: 5px 0;">{{ total_savings }}</span>
                                <span style="color: #065f46; font-size: 13px;">Don't lose your exclusive discounts and delivery savings!</span>
                            </div>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ renewal_url }}" style="background-color: #fcb800; color: #222; font-weight: bold; text-decoration: none; padding: 14px 36px; border-radius: 6px; display: inline-block; font-size: 15px;">
                                    Renew My Membership (1-Click)
                                </a>
                            </p>

                            <p style="font-size: 14px; color: #777;">
                                If you have questions about renewing or upgrading, we're here to help anytime.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
