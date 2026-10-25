{{ header }}

<table width="100%">
    <tbody>
        <tr>
            <td class="wrapper" width="700" align="center">
                <table class="section" width="700" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="column" width="700" valign="top">
                            <h2 style="color: #222; font-weight: 700; margin-bottom: 15px;">Daily Membership Expiration Digest</h2>
                            <p style="font-size: 15px; color: #555; line-height: 24px;">
                                Daily automated membership status report for <strong>{{ site_title }}</strong>:
                            </p>

                            <table style="width: 100%; border: 1px solid #e1e1e1; border-radius: 8px; margin: 25px 0; padding: 20px; background-color: #f9f9f9;">
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Memberships Expiring in 7 Days:</strong></td>
                                    <td style="padding: 8px 0; font-weight: bold; color: #f59e0b;">{{ expiring_count }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;"><strong>Memberships Lapsed Today:</strong></td>
                                    <td style="padding: 8px 0; font-weight: bold; color: #ef4444;">{{ expired_count }}</td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; color: #777;">
                                You can view and manage all memberships in your <a href="{{ site_url }}/admin-panel/memberships">Admin Dashboard</a>.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>

{{ footer }}
