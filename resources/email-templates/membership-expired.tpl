{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Your {{ plan_name }} Membership Has Expired</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Hi {{ customer_name }}, your <strong>{{ plan_name }}</strong> membership on <strong>{{ site_title }}</strong> has expired today.
                            </p>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                We loved having you as a member! You can easily reactivate your membership whenever you're ready to unlock exclusive savings again.
                            </p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ renewal_url }}" style="background-color: #fcb800; color: #222; font-weight: bold; text-decoration: none; padding: 14px 36px; border-radius: 6px; display: inline-block; font-size: 15px;">
                                    Reactivate My Membership
                                </a>
                            </p>

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
