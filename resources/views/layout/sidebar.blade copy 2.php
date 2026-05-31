<nav class="sidebar">
  <div class="sidebar-header">
    <a href="#" class="sidebar-brand">
      {{config('app.name')}}
    </a>
    <div class="sidebar-toggler not-active">
      <span></span>
      <span></span>
      <span></span>
    </div>
  </div>
  @php
    $acct = auth()->user()->account_type;
  @endphp
  <div class="sidebar-body">
    <ul class="nav">
      <li class="nav-item nav-category">Main</li>
      <li
      class="nav-item {{ active_class(['/']) }}">
        <a href="{{ url('/') }}" class="nav-link">
          <i class="link-icon" data-feather="box"></i>
          <span class="link-title">Dashboard</span>
        </a>
      </li>
      @if ($acct == 'admin' or $acct == 'manager')
        {{--sale ----------------------------------------------------------------------------------------------------- --}}
          <li class="nav-item nav-category">Trade Management</li>
          <li class="nav-item {{ active_class([Request::get('test') == 1?null:'sale/*','sale/create','purchase/*','purchase/create']) }}">
            <a class="nav-link" data-toggle="collapse" href="#sale" role="button" aria-expanded="{{ is_active_route([Request::get('test') == 1?null:'sale/*','sale/create','purchase/*','purchase/create']) }}" aria-controls="email">
              <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>

              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag link-icon"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>

            <span class="link-title">Trade</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class([Request::get('test') == 1?null:'sale/*','sale/create','purchase/*','purchase/create']) }}" id="sale">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('sale.create') }}" class="nav-link {{ active_class([Request::get('test') == 1?null:'sale/*','sale/create']) }}">Sales</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('purchase.create') }}" class="nav-link {{ active_class(['purchase/*','purchase/create']) }}">Purchases</a>
                </li>
              </ul>
            </div>
          </li>
        {{-- sale ----------------------------------------------------------------------------------------------------- --}}
        {{--Ledgers ----------------------------------------------------------------------------------------------------- --}}
          <li class="nav-item nav-category">Ledgers Management</li>
          <li class="nav-item {{ active_class([Request::get('test') == 1?null:'custledger/*',Request::get('test') == 1?null:'custledger',Request::get('test') == 1?null:'supledger/*',Request::get('test') == 1?null:'supledger']) }}">
            <a class="nav-link" data-toggle="collapse" href="#custledger" role="button" aria-expanded="{{ is_active_route([Request::get('test') == 1?null:'custledger/*',Request::get('test') == 1?null:'custledger',Request::get('test') == 1?null:'supledger/*',Request::get('test') == 1?null:'supledger']) }}" aria-controls="email">
              <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign link-icon"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
              <span class="link-title">Ledgers</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class([Request::get('test') == 1?null:'custledger/*',Request::get('test') == 1?null:'custledger',Request::get('test') == 1?null:'supledger/*',Request::get('test') == 1?null:'supledger']) }}" id="custledger">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('custledger.index') }}" class="nav-link {{ active_class([Request::get('test') == 1?null:'custledger/*',Request::get('test') == 1?null:'custledger']) }}">Customer Ledger</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('supledger.index') }}" class="nav-link {{ active_class([Request::get('test') == 1?null:'supledger/*',Request::get('test') == 1?null:'supledger']) }}">Supplier Ledger</a>
                </li>
              </ul>
            </div>
          </li>
        {{-- ledgers ----------------------------------------------------------------------------------------------------- --}}
        {{--Payments ----------------------------------------------------------------------------------------------------- --}}
          <li class="nav-item nav-category">Payments Management</li>
          <li class="nav-item {{ active_class(['tra/*','tra','ctra/*','ctra']) }}">
            <a class="nav-link" data-toggle="collapse" href="#tran" role="button" aria-expanded="{{ is_active_route(['tra/*','tra','ctra/*','ctra']) }}" aria-controls="tra">
              <svg  width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card link-icon"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
              <span class="link-title">Payments</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(['tra/*','tra','ctra/*','ctra']) }}" id="tran">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('ctra.index') }}" class="nav-link {{ active_class(['ctra/*','ctra']) }}">Customer Payments</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('tra.index') }}" class="nav-link {{ active_class(['tra/*','tra']) }}">Supplier Payments</a>
                </li>
              </ul>
            </div>
          </li>
        {{-- payments ----------------------------------------------------------------------------------------------------- --}}
        {{-- Fuell ----------------------------------------------------------------------------------------------------- --}}
          <li class="nav-item nav-category">Fuel Management</li>
          <li class="nav-item {{ active_class(['products/*','products']) }}">
            <a href="{{ route('products.index') }}" class="nav-link">
              <i class="fas fa-gas-pump link-icon"></i>
              <span class="link-title">Fuels</span></a>
          </li>
          <li class="nav-item {{ active_class(['stock/*','stock']) }}">
            <a href="{{ route('stock.index') }}" class="nav-link">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-battery link-icon"><rect x="1" y="6" width="18" height="12" rx="2" ry="2"></rect><line x1="23" y1="13" x2="23" y2="11"></line></svg>
              <span class="link-title">Fuel Stocks</span>
            </a>
          </li>
          <li class="nav-item {{ active_class(['backup/*','backup']) }}">
            <a href="{{ route('backup.index') }}" class="nav-link ">

              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-battery-charging link-icon"><path d="M5 18H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h3.19M15 6h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-3.19"></path><line x1="23" y1="13" x2="23" y2="11"></line><polyline points="11 6 7 12 13 12 9 18"></polyline></svg>
              <span class="link-title">Fuel Backup</span></a>
          </li>
          <li class="nav-item {{ active_class(['dip/*','dip']) }}">
            <a href="{{ route('dip.index') }}" class="nav-link ">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="link-icon feather feather-thermometer"><path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path></svg>
              <span class="link-title">Fuel Dips</span></a>
          </li>
        {{-- Fuell ----------------------------------------------------------------------------------------------------- --}}
        {{-- user --}}
            <li class="nav-item nav-category">User & Roles Management</li>
            <li class="nav-item {{ active_class(['user/*','user']) }}">
                <a href="{{ route('user.index') }}" class="nav-link ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="link-title">Users</span>
                </a>
            </li>

            <li class="nav-item {{ active_class(['roles/*','roles']) }}">
                <a href="{{ route('roles.index') }}" class="nav-link ">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="link-title">Roles</span>
                </a>
            </li>
        {{-- user --}}
        {{-- Traders ----------------------------------------------------------------------------------------------------- --}}

          <li class="nav-item nav-category">Traders Management</li>
          <li class="nav-item {{ active_class(['supplier/*','supplier','customer/*','customer']) }}">
            <a class="nav-link" data-toggle="collapse" href="#trader" role="button" aria-expanded="{{ is_active_route(['supplier/*','supplier','customer/*','customer']) }}" aria-controls="email">
              <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
              <span class="link-title">Traders</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(['supplier/*','supplier','customer/*','customer']) }}" id="trader">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('customer.index') }}" class="nav-link {{ active_class(['customer/*','customer']) }}">Customers</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('supplier.index') }}" class="nav-link {{ active_class(['supplier/*','supplier']) }}">Suppliers</a>
                </li>
              </ul>
            </div>
          </li>
        {{-- Traders ----------------------------------------------------------------------------------------------------- --}}
        {{-- expense ----------------------------------------------------------------------------------------------------- --}}
          <li class="nav-item nav-category">Expense Management</li>
          <li class="nav-item {{ active_class(['exptype/*','exptype','exp/*','exp']) }}">
            <a class="nav-link" data-toggle="collapse" href="#exp" role="button" aria-expanded="{{ is_active_route(['exptype/*','exptype','exp/*','exp']) }}" aria-controls="email">
              <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>

              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello link-icon"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
              <span class="link-title">Expenses</span>
              <i class="link-arrow" data-feather="chevron-down"></i>
            </a>
            <div class="collapse {{ show_class(['exptype/*','exptype','exp/*','exp']) }}" id="exp">
              <ul class="nav sub-menu">
                <li class="nav-item">
                  <a href="{{ route('exp.index') }}" class="nav-link {{ active_class(['exp/*','exp']) }}">Expenses</a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('exptype.index') }}" class="nav-link {{ active_class(['exptype/*','exptype']) }}">Expense Types</a>
                </li>
              </ul>
            </div>
          </li>
        {{-- expense ----------------------------------------------------------------------------------------------------- --}}
        {{-- Reports--------------------------------------------------- --}}
            <li class="nav-item nav-category">Reports Management</li>
            <li class="nav-item {{ active_class(['report/credit/*','report/credit',Request::get('test') == 1?'supledger':null,Request::get('test') == 1?'custledger':null]) }}">
                <a class="nav-link" data-toggle="collapse" href="#rep" role="button" aria-expanded="{{ is_active_route(['report/credit/*','report/credit',Request::get('test') == 1?'supledger':null,Request::get('test') == 1?'custledger':null]) }}" aria-controls="email">
                <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span class="link-title">Ledgers</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['report/credit/*','report/credit',Request::get('test') == 1?'supledger':null,Request::get('test') == 1?'custledger':null]) }}" id="rep">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                    <a href="{{ route('report.credit') }}" class="nav-link {{ active_class(['report/credit','report/credit/*',Request::get('test') == 1?'supledger':null,Request::get('test') == 1?'custledger':null]) }}">Credit Report</a>
                    </li>
                </ul>
                </div>
            </li>

            <li class="nav-item {{ active_class(['report/sale/dailysale','report/sale/dailysale/*',Request::get('test') != 1?null:'sale/*','report/sale/profit','report/sale/profit/*']) }}">
                <a class="nav-link" data-toggle="collapse" href="#dsa" role="button" aria-expanded="{{ is_active_route(['report/sale/dailysale','report/sale/dailysale/*','report/sale/profit','report/sale/profit/*',Request::get('test') != 1?null:'sale/*','report/sale/dailysaleitem/*','report/sale/profitfilter','report/sale/profitfilter/*']) }}" aria-controls="email">
                <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-trending-up"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                <span class="link-title">Sales</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['report/sale/dailysale','report/sale/dailysale/*','report/sale/profit','report/sale/profit/*',Request::get('test') != 1?null:'sale/*','report/sale/dailysaleitem/*','report/sale/profitfilter','report/sale/profitfilter/*']) }}" id="dsa">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                    <a href="{{ route('report.sale.dailysale') }}" class="nav-link {{ active_class(['report/sale/dailysale','report/sale/dailysale/*',Request::get('test') != 1?null:'sale/*','report/sale/dailysaleitem/*']) }}">Daily Sales</a>
                    </li>
                    <li class="nav-item">
                    <a href="{{ route('report.sale.profit') }}" class="nav-link {{ active_class(['report/sale/profit','report/sale/profit/*','report/sale/profitfilter','report/sale/profitfilter/*']) }}">Profit & Loss</a>
                    </li>
                </ul>
                </div>
            </li>
            <li class="nav-item {{ active_class(['report/expense','report/expense/*','report/expensefilter','report/expensefilter/*']) }}">
                <a class="nav-link" data-toggle="collapse" href="#expe" role="button" aria-expanded="{{ is_active_route(['report/expense','report/expense/*','report/expensefilter','report/expensefilter/*']) }}" aria-controls="email">
                <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trello link-icon"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="9"></rect><rect x="14" y="7" width="3" height="5"></rect></svg>
                <span class="link-title">Expenses</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['report/expense','report/expense/*','report/expensefilter','report/expensefilter/*']) }}" id="expe">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                    <a href="{{ route('report.expense') }}" class="nav-link {{ active_class(['report/expense','report/expense/*','report/expensefilter','report/expensefilter/*']) }}">Monthly Expense Report</a>
                    </li>
                </ul>
                </div>
            </li>

            <li class="nav-item {{ active_class(['report/prices','report/prices/*']) }}">
                <a class="nav-link" data-toggle="collapse" href="#price" role="button" aria-expanded="{{ is_active_route(['report/prices','report/prices/*']) }}" aria-controls="email">
                <script src='{{asset('js/fa.js')}}' crossorigin='anonymous'></script>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather link-icon feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                <span class="link-title">Fuel Prices</span>
                <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse {{ show_class(['report/prices','report/prices/*']) }}" id="price">
                <ul class="nav sub-menu">
                    <li class="nav-item">
                    <a href="{{ route('report.price') }}" class="nav-link {{ active_class(['report/prices','report/prices/*']) }}">Fuel Price Report</a>
                    </li>
                </ul>
                </div>
            </li>
        {{-- Reports--------------------------------------------------- --}}
      @endif


    </ul>
  </div>
</nav>



{{-- light dark sidebar theme setting --}}
{{-- <nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted">Sidebar:</h6>
    <div class="form-group border-bottom">
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" >
          Light
        </label>
      </div>
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark" checked>
          Dark
        </label>
      </div>
    </div>
    <div class="theme-wrapper">
      <h6 class="text-muted mb-2">Light Version:</h6>
      <a class="theme-item active" href="#">
        <img src="{{ url('assets/images/screenshots/light.jpg') }}" alt="light version">
      </a>
      <h6 class="text-muted mb-2">Dark Version:</h6>
      <a class="theme-item" href="https://www.nobleui.com/laravel/template/dark">
        <img src="{{ url('assets/images/screenshots/dark.jpg') }}" alt="light version">
      </a>
    </div>
  </div>
</nav> --}}
