{{ header }}

<p>Hi {{ customer_name }}!</p>
<p>We hope you enjoyed the benefits of being a member of {{ site_title }}. We wanted to let you know that your membership has expired as of {{ membership_expire_date }}.</p>
<p>As a member, you had access to exclusive deals, special discounts, and personalized recommendations. We don't want you to miss out on these perks. Renew your membership today to continue enjoying all these benefits.</p>
<p>If you have any questions or need assistance with renewing your membership, please don't hesitate to contact our support team.</p>

<p><a href="{{ site_url }}/memberships" class="action-button" style="border: solid 1px #fff;
    -webkit-border-radius: 7px;
    -moz-border-radius: 7px;
    border-radius: 7px;
    font-size: 14px;
    font-weight: bold;
    font-style: normal;
    font-stretch: normal;
    line-height: 1.43;
    letter-spacing: 0.5px;
    text-align: center;
    color: #fff !important;
    text-decoration: none !important;
    padding: 10px 30px;
    margin: 0 0 30px;
    display: inline-block;
    text-transform: uppercase;
    background-color: #1280f5;">Renew Now</a></p>

<p>Thank you for being a part of {{ site_title }}.</p>
<p>Best regards,</p>

{{ footer }}