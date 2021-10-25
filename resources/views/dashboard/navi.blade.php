<div class="col-md-3">
  <div class="panel panel-default">
    <div class="panel-heading">Navigation</div>

    <div class="panel-body">
      <ul>
        <li>
          <a href="/dashboard">
            Dashboard
          </a>
        </li>
        <li>
          <a href="/dashboard/orders">
            Orders
          </a>
        </li>
        <li>
          <a href="/dashboard/domains-overview">
            Domains Overview
          </a>
        </li>
        <li>
          <a href="/dashboard/domains/add">
            Add New Domain
          </a>
        </li>
        <li>
          <a href="/dashboard/domains/bulk-add">
            Bulk Upload Domains
          </a>
        </li>
        <li>
          <a href="/dashboard/premium-submission-overview">
            Premium Submission
          </a>
        </li>
        <li>
          <a href="/dashboard/premium-domain-overview">
            Premium Domain Overview
          </a>
        </li>
        <li>
          <a href="/dashboard/price-drop">
            Price Drop
          </a>
        </li>
        <li>
          <a href="/dashboard/price-drop-overview">
            Price Drop Overview
          </a>
        </li>
        <li>
          <a href="/dashboard/domain-offer-price">
            Domain Offers
          </a>
        </li>
        <li>
          <a href="/dashboard/my-profile">
            My Profile
          </a>
        </li>
        @if (auth()->user()->plan === 'pro' || auth()->user()->plan === 'Unlimited' || auth()->user()->isAdmin)
          <li>
            <a href="/dashboard/marketing-tools">
              Marketing Tools
            </a>
          </li>
        @endif
        <li>
          <a href="/dashboard/subscription">
            My Subscription
          </a>
        </li>
        <li>
          <a href="/dashboard/payment-gateways">
            Payment Gateways
          </a>
        </li>
        <li>
          <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Log Out
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
