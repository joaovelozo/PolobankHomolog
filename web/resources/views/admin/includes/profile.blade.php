 <!-- ========== Left Sidebar Start ========== -->
 <div class="left-side-menu">
  <div class="media user-profile mt-2 mb-2">
      <img src="{{ (!empty($adminData->avatar)) ? url('uploads/admin_images/'.$adminData->avatar):url('uploads/noimage.png') }}" class="avatar-sm rounded-circle mr-2" alt="Shreyu" />
      <img src="{{ (!empty($adminData->avatar)) ? url('uploads/admin_images/'.$adminData->avatar):url('uploads/noimage.png') }}" class="avatar-xs rounded-circle mr-2" alt="Shreyu" />

      <div class="media-body">
          <h6 class="pro-user-name mt-0 mb-0">{{Auth::user()->name}}</h6>

      </div>
      <div class="dropdown align-self-center profile-dropdown-menu">
          <a class="dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
              aria-expanded="false">
              <span data-feather="chevron-down" ></span>
          </a>
          <div class="dropdown-menu profile-dropdown">
              <a href="{{route('admin.profile')}}" class="dropdown-item notify-item">
                  <i data-feather="user" class="icon-dual icon-xs mr-2" ></i>
                  <span>Meu Perfil</span>
              </a>




              <div class="dropdown-divider"></div>
              <form method="POST" action="{{route('logout')}}">
                @csrf
              <a href="#" onclick="event.preventDefault();
              this.closest('form').submit();" class="dropdown-item notify-item">
                  <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                  <span>Sair</span>
              </a>
              </form>
          </div>
      </div>
  </div>
