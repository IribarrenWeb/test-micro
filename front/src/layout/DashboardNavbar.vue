<template>
  <base-nav
    class="navbar-top navbar-dark"
    id="navbar-main"
    :show-toggle-button="false"
    expand
  >
    <form
      class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto"
    >
    </form>
    <ul class="navbar-nav align-items-center d-none d-md-flex">
      <li class="nav-item dropdown">
        <base-dropdown class="nav-link pr-0">
          <template v-slot:title>
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img
                  alt="Image placeholder"
                  src="img/theme/team-4-800x800.jpg"
                />
              </span>
              <div class="media-body ml-2 d-none d-lg-block">
                <span class="mb-0 text-sm font-weight-bold">{{me.name}} {{me.last_name}}</span>
              </div>
            </div>
          </template>
          <div class="dropdown-header noti-title">
            <h6 class="text-overflow m-0">Bienvenido!</h6>
          </div>
          <!-- <router-link to="/profile" class="dropdown-item">
            <i class="ni ni-single-02"></i>
            <span>My profile</span>
          </router-link> -->
          <a @click.prevent="logout()" class="dropdown-item">
            <i class="ni ni-user-run"></i>
            <span>Logout</span>
          </a>
        </base-dropdown>
      </li>
    </ul>
  </base-nav>
</template>
<script>
export default {
  data() {
    return {
      activeNotifications: false,
      showMenu: false,
      searchQuery: "",
    };
  },
  props:{
      me:{
        type: Object,
        default() {
          return {name: 'Username', last_name: 'Last name'}
        }
      },
  },
  methods: {
    toggleSidebar() {
      this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
    },
    hideSidebar() {
      this.$sidebar.displaySidebar(false);
    },
    toggleMenu() {
      this.showMenu = !this.showMenu;
    },
    logout(){
        this.$store.dispatch("logout")
      }
  },
};
</script>
