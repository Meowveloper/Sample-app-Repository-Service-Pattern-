<template>
  <aside class="w-1/6 bg-side-bar h-screen fixed pt-7.5 overflow-auto">
    <!-- main container -->
    <div class="menu-container">
      <h4 class="text-grey ml-8 main-menu">Main Menu</h4>
      <ul class="mt-5">
        <router-link
          to="/dashboard"
          class="flex items-center pl-7 h-11.25"
          :class="{ active: isActive('/dashboard') }"
        >
          <li class="flex items-center text-grey">
            <iconify-icon icon="ri:dashboard-line" width="20"></iconify-icon>
            <h5 class="menu-label ml-4">Dashboard</h5>
          </li>
        </router-link>

        <router-link
          to="/categories"
          class="flex items-center pl-7 h-11.25"
          :class="{ active: isActive('/categories') }"
        >
          <li class="flex items-center text-grey">
            <iconify-icon
              icon="material-symbols:category"
              width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Categories</h5>
          </li>
        </router-link>

        <router-link
          to="/problems"
          exact
          class="flex items-center pl-7 h-11.25"
          :class="{ active: isActive('/problems') }"
        >
          <li class="flex items-center text-grey">
            <iconify-icon
              icon="solar:document-text-bold"
              width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Problems</h5>
          </li>
        </router-link>

        <router-link
          to="/admins"
          class="flex items-center pl-7 h-11.25"
          :class="{ active: isActive('/admins') }"
        >
          <li class="flex items-center text-grey">
            <iconify-icon
              icon="material-symbols:admin-panel-settings"
              width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Admins</h5>
          </li>
        </router-link>
        <router-link
          to="/users"
          class="flex items-center pl-7 h-11.25"
          :class="{ active: isActive('/users') }"
        >
          <li class="flex items-center text-grey">
            <iconify-icon
              icon="fa6-solid:users"
              width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Users</h5>
          </li>
        </router-link>

        <button
            @click="logout"
            class="flex items-center pl-7 h-11.25"
        >
            <li class="flex items-center text-grey">
            <iconify-icon
                icon="material-symbols:logout"
                width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Logout</h5>
            </li>
        </button>
      </ul>
    </div>
    <!-- setting container -->
    <div
      class="setting-container pt-4 w-full"
      :class="{ 'absolute bottom-px': isPositionAbsolute }"
    >
      <h4 class="text-grey ml-8 main-menu">Setting</h4>
      <ul class="dropdown-menu">
        <a href="/dashboard" class="flex items-center pl-7 h-11.25">
          <li class="flex items-center text-grey">
            <iconify-icon icon="uil:setting" width="20"></iconify-icon>
            <h5 class="menu-label ml-4">Setting</h5>
          </li>
        </a>
        <a href="/dashboard" class="flex items-center pl-7 h-11.25">
          <li class="flex items-center text-grey">
            <iconify-icon
              icon="material-symbols:help"
              width="20"
            ></iconify-icon>
            <h5 class="menu-label ml-4">Help</h5>
          </li>
        </a>
      </ul>
    </div>
  </aside>
</template>
<script>
import axios from 'axios';
export default {

  data() {
    return {
      isCollectionOpen: false,
      isCollectionTrigger: false,
      isPaymentOpen: false,
      isAdminToolOpen: false,
      isSettingOverflow: false,
    };
  },
  methods: {

    isActive(route) {
      return this.$route.path.startsWith(route);
    },
    toggleCollection() {
      this.isCollectionOpen = !this.isCollectionOpen;
    },
    togglePayment() {
      this.isPaymentOpen = !this.isPaymentOpen;
    },
    toggleAdminTool() {
      this.isAdminToolOpen = !this.isAdminToolOpen;
    },
    isActiveAdminTool() {
      // Use the current route to determine if isAdminToolOpen should be true
      const currentRoute = window.location.pathname;

      // Define an array of routes that should open the admin tool
      const adminToolRoutes = [
        "/users",
        "/cities",
        "/gates",
        "/townships",
        "/itemtypes",
        "/delivery-types",
        "/third-party-vendor",
      ];
      if (adminToolRoutes.includes(currentRoute)) {
        this.isAdminToolOpen = true;
      } else {
        this.isAdminToolOpen = false;
      }
    },
    async logout() {
        try {
            const response = await axios.post('/api/logout', {}, {
                headers: this.csrf
            });
            
            window.location.href = response.data.redirect;

        } catch (error) {
            console.error('Logout failed:', error.message);
        }
    }
  },
  computed: {
    isPositionAbsolute() {
      return this.isCollectionOpen || this.isPaymentOpen || this.isAdminToolOpen
        ? false
        : true;
    },
    csrf() {
      return document.querySelector('meta[name="_token"]').content;
    },
  },
  mounted() {
    this.isActiveAdminTool();
  },
};
</script>
<style scoped>
aside::-webkit-scrollbar {
  display: none;
}

.main-menu {
  font-size: 10px !important;
}

.menu-label {
  font-size: 14px !important;
}

.sub-active,
.active {
  color: #ffffff;
  background-color: #07384d;
  border-left: 6px solid #116a5b;
}

.active {
  padding-left: 22px !important;
}
.sub-active {
  padding-left: 50px !important;
}

.active > * {
  color: #ffffff;
}

.rotate-180 {
  transform: rotate(180deg);
  transition: transform 0.5s ease;
}

.dropdown-menu > a {
  border-bottom: 1px solid #07384d;
}
</style>
