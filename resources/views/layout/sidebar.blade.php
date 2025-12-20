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
          <li class="nav-item nav-category">User Management</li>
            <li class="nav-item {{ active_class(['user/*','user']) }}">
            <a href="{{ route('user.index') }}" class="nav-link ">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users link-icon"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            <span class="link-title">Users</span></a>
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

      
     <!--<li class="nav-item nav-category">web apps</li>-->
     <!-- <li class="nav-item {{ active_class(['email/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#email" role="button" aria-expanded="{{ is_active_route(['email/*']) }}" aria-controls="email">-->
     <!--     <i class="link-icon" data-feather="mail"></i>-->
     <!--     <span class="link-title">Email</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['email/*']) }}" id="email">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/email/inbox') }}" class="nav-link {{ active_class(['email/inbox']) }}">Inbox</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/email/read') }}" class="nav-link {{ active_class(['email/read']) }}">Read</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/email/compose') }}" class="nav-link {{ active_class(['email/compose']) }}">Compose</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['apps/chat']) }}">-->
     <!--   <a href="{{ url('/apps/chat') }}" class="nav-link">-->
     <!--     <i class="link-icon" data-feather="message-square"></i>-->
     <!--     <span class="link-title">Chat</span>-->
     <!--   </a>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['apps/calendar']) }}">-->
     <!--   <a href="{{ url('/apps/calendar') }}" class="nav-link">-->
     <!--     <i class="link-icon" data-feather="calendar"></i>-->
     <!--     <span class="link-title">Calendar</span>-->
     <!--   </a>-->
     <!-- </li>-->


     <!-- <li class="nav-item nav-category">Components</li>-->
     <!-- <li class="nav-item {{ active_class(['ui-components/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#uiComponents" role="button" aria-expanded="{{ is_active_route(['ui-components/*']) }}" aria-controls="uiComponents">-->
     <!--     <i class="link-icon" data-feather="feather"></i>-->
     <!--     <span class="link-title">UI Kit</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['ui-components/*']) }}" id="uiComponents">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/alerts') }}" class="nav-link {{ active_class(['ui-components/alerts']) }}">Alerts</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/badges') }}" class="nav-link {{ active_class(['ui-components/badges']) }}">Badges</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/breadcrumbs') }}" class="nav-link {{ active_class(['ui-components/breadcrumbs']) }}">Breadcrumbs</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/buttons') }}" class="nav-link {{ active_class(['ui-components/buttons']) }}">Buttons</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/button-group') }}" class="nav-link {{ active_class(['ui-components/button-group']) }}">Button group</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/cards') }}" class="nav-link {{ active_class(['ui-components/cards']) }}">Cards</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/carousel') }}" class="nav-link {{ active_class(['ui-components/carousel']) }}">Carousel</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/collapse') }}" class="nav-link {{ active_class(['ui-components/collapse']) }}">Collapse</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/dropdowns') }}" class="nav-link {{ active_class(['ui-components/dropdowns']) }}">Dropdowns</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/list-group') }}" class="nav-link {{ active_class(['ui-components/list-group']) }}">List group</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/media-object') }}" class="nav-link {{ active_class(['ui-components/media-object']) }}">Media object</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/modal') }}" class="nav-link {{ active_class(['ui-components/modal']) }}">Modal</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/navs') }}" class="nav-link {{ active_class(['ui-components/navs']) }}">Navs</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/navbar') }}" class="nav-link {{ active_class(['ui-components/navbar']) }}">Navbar</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/pagination') }}" class="nav-link {{ active_class(['ui-components/pagination']) }}">Pagination</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/popovers') }}" class="nav-link {{ active_class(['ui-components/popovers']) }}">Popvers</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/progress') }}" class="nav-link {{ active_class(['ui-components/progress']) }}">Progress</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/scrollbar') }}" class="nav-link {{ active_class(['ui-components/scrollbar']) }}">Scrollbar</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/scrollspy') }}" class="nav-link {{ active_class(['ui-components/scrollspy']) }}">Scrollspy</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/spinners') }}" class="nav-link {{ active_class(['ui-components/spinners']) }}">Spinners</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/tabs') }}" class="nav-link {{ active_class(['ui-components/tabs']) }}">Tabs</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/ui-components/tooltips') }}" class="nav-link {{ active_class(['ui-components/tooltips']) }}">Tooltips</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['advanced-ui/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#advanced-ui" role="button" aria-expanded="{{ is_active_route(['advanced-ui/*']) }}" aria-controls="advanced-ui">-->
     <!--     <i class="link-icon" data-feather="anchor"></i>-->
     <!--     <span class="link-title">Advanced UI</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['advanced-ui/*']) }}" id="advanced-ui">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/advanced-ui/cropper') }}" class="nav-link {{ active_class(['advanced-ui/cropper']) }}">Cropper</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/advanced-ui/owl-carousel') }}" class="nav-link {{ active_class(['advanced-ui/owl-carousel']) }}">Owl Carousel</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/advanced-ui/sweet-alert') }}" class="nav-link {{ active_class(['advanced-ui/sweet-alert']) }}">Sweet Alert</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['forms/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#forms" role="button" aria-expanded="{{ is_active_route(['forms/*']) }}" aria-controls="forms">-->
     <!--     <i class="link-icon" data-feather="inbox"></i>-->
     <!--     <span class="link-title">Forms</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['forms/*']) }}" id="forms">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/forms/basic-elements') }}" class="nav-link {{ active_class(['forms/basic-elements']) }}">Basic Elements</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/forms/advanced-elements') }}" class="nav-link {{ active_class(['forms/advanced-elements']) }}">Advanced Elements</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/forms/editors') }}" class="nav-link {{ active_class(['forms/editors']) }}">Editors</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/forms/wizard') }}" class="nav-link {{ active_class(['forms/wizard']) }}">Wizard</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['charts/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#charts" role="button" aria-expanded="{{ is_active_route(['charts/*']) }}" aria-controls="charts">-->
     <!--     <i class="link-icon" data-feather="pie-chart"></i>-->
     <!--     <span class="link-title">Charts</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['charts/*']) }}" id="charts">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/apex') }}" class="nav-link {{ active_class(['charts/apex']) }}">Apex</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/chartjs') }}" class="nav-link {{ active_class(['charts/chartjs']) }}">ChartJs</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/flot') }}" class="nav-link {{ active_class(['charts/flot']) }}">Flot</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/morrisjs') }}" class="nav-link {{ active_class(['charts/morrisjs']) }}">MorrisJs</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/peity') }}" class="nav-link {{ active_class(['charts/peity']) }}">Peity</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/charts/sparkline') }}" class="nav-link {{ active_class(['charts/sparkline']) }}">Sparkline</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['tables/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#tables" role="button" aria-expanded="{{ is_active_route(['tables/*']) }}" aria-controls="tables">-->
     <!--     <i class="link-icon" data-feather="layout"></i>-->
     <!--     <span class="link-title">Tables</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['tables/*']) }}" id="tables">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/tables/basic-tables') }}" class="nav-link {{ active_class(['tables/basic-tables']) }}">Basic Tables</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/tables/data-table') }}" class="nav-link {{ active_class(['tables/data-table']) }}">Data Table</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['icons/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#icons" role="button" aria-expanded="{{ is_active_route(['icons/*']) }}" aria-controls="icons">-->
     <!--     <i class="link-icon" data-feather="smile"></i>-->
     <!--     <span class="link-title">Icons</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['icons/*']) }}" id="icons">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/icons/feather-icons') }}" class="nav-link {{ active_class(['icons/feather-icons']) }}">Feather Icons</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/icons/flag-icons') }}" class="nav-link {{ active_class(['icons/flag-icons']) }}">Flag Icons</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/icons/mdi-icons') }}" class="nav-link {{ active_class(['icons/mdi-icons']) }}">Mdi Icons</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item nav-category">Pages</li>-->
     <!-- <li class="nav-item {{ active_class(['general/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#general" role="button" aria-expanded="{{ is_active_route(['general/*']) }}" aria-controls="general">-->
     <!--     <i class="link-icon" data-feather="book"></i>-->
     <!--     <span class="link-title">Special Pages</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['general/*']) }}" id="general">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/blank-page') }}" class="nav-link {{ active_class(['general/blank-page']) }}">Blank page</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/faq') }}" class="nav-link {{ active_class(['general/faq']) }}">Faq</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/invoice') }}" class="nav-link {{ active_class(['general/invoice']) }}">Invoice</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/profile') }}" class="nav-link {{ active_class(['general/profile']) }}">Profile</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/pricing') }}" class="nav-link {{ active_class(['general/pricing']) }}">Pricing</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/general/timeline') }}" class="nav-link {{ active_class(['general/timeline']) }}">Timeline</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['auth/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#auth" role="button" aria-expanded="{{ is_active_route(['auth/*']) }}" aria-controls="auth">-->
     <!--     <i class="link-icon" data-feather="unlock"></i>-->
     <!--     <span class="link-title">Authentication</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['auth/*']) }}" id="auth">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/auth/login') }}" class="nav-link {{ active_class(['auth/login']) }}">Login</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/auth/register') }}" class="nav-link {{ active_class(['auth/register']) }}">Register</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item {{ active_class(['error/*']) }}">-->
     <!--   <a class="nav-link" data-toggle="collapse" href="#error" role="button" aria-expanded="{{ is_active_route(['error/*']) }}" aria-controls="error">-->
     <!--     <i class="link-icon" data-feather="cloud-off"></i>-->
     <!--     <span class="link-title">Error</span>-->
     <!--     <i class="link-arrow" data-feather="chevron-down"></i>-->
     <!--   </a>-->
     <!--   <div class="collapse {{ show_class(['error/*']) }}" id="error">-->
     <!--     <ul class="nav sub-menu">-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/error/404') }}" class="nav-link {{ active_class(['error/404']) }}">404</a>-->
     <!--       </li>-->
     <!--       <li class="nav-item">-->
     <!--         <a href="{{ url('/error/500') }}" class="nav-link {{ active_class(['error/500']) }}">500</a>-->
     <!--       </li>-->
     <!--     </ul>-->
     <!--   </div>-->
     <!-- </li>-->
     <!-- <li class="nav-item nav-category">Docs</li>-->
     <!-- <li class="nav-item">-->
     <!--   <a href="https://www.nobleui.com/laravel/documentation/docs.html" target="_blank" class="nav-link">-->
     <!--     <i class="link-icon" data-feather="hash"></i>-->
     <!--     <span class="link-title">Documentation</span>-->
     <!--   </a>-->
     <!-- </li>-->
      {{-- ------------------------------------- Extra---------------------------------------- --}} 

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