<template>

  <div id="kt_header_user_menu_toggle" class="app-navbar-item ms-1 ms-md-3">

    <div class="cursor-pointer symbol symbol-30px symbol-md-40px"
         data-kt-menu-attach="parent"
         data-kt-menu-placement="bottom-end"
         data-kt-menu-trigger="{default: 'click', lg: 'hover'}">
      <img alt="user" src="@/assets/media/avatars/profile.png" />
    </div>


    <div
      class="menu menu-sub menu-sub-dropdown menu-column
      menu-rounded menu-gray-800 menu-state-bg menu-state-color f
      w-semibold py-4 fs-6 w-275px"
      data-kt-menu="true">


      <div class="menu-item px-3">
        <div class="menu-content d-flex align-items-center px-3">


          <div class="symbol symbol-50px me-5">
            <img alt="Logo" src="@/assets/media/avatars/profile.png" />
          </div>


          <div class="d-flex flex-column">
            <div class="fw-bold d-flex align-items-center fs-5">
            {{ user.fullName}}
            </div>

            <a class="fw-semibold text-muted text-hover-primary fs-7" href="#">
              {{user.email}}
            </a>
          </div>
        </div>
      </div>

      <div class="separator my-2"></div>

      <div class="menu-item px-5">
       <router-link class="menu-link px-5" to="/user/profile/9999" >
          My Profile
        </router-link>
      </div>

      <div class="separator my-2"></div>

      <div class="menu-item px-5"
           data-kt-menu-offset="-15px, 0"
           data-kt-menu-placement="left-start"
           data-kt-menu-trigger="{default: 'click', lg: 'hover'}">

        <a @click.prevent="tabSelector('accountSettings')" class="menu-link px-5">
            <span class="menu-title position-relative">
              Language
              <span class="fs-8 rounded bg-light px-3 py-2 position-absolute translate-middle-y top-50 end-0">
                English
              </span>
            </span>
        </a>
      </div>

      <div class="menu-item px-5 my-1">
        <a class="menu-link px-5" @click.prevent="tabSelector('accountSettings')">
          Account Settings
        </a>
      </div>

      <div class="menu-item px-5">
        <router-link class="menu-link px-5" to="/signout" >
          Sign Out
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import vueDataStore from "@/services/storage";

export default {
  name: "UserMenu",
  data(){
    return {
      user: {}
    }
  },
 methods: {
   signOut() {
     this.$emit("sign_out", true);
   },

   tabSelector(tab) {
     this.$emit("curr_tab", tab);
   }
 },
  created() {
    this.user = vueDataStore.getObject("userProfile");
  }
};
</script>

<style scoped>

</style>