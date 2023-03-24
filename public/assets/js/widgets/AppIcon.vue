<template>
  <!-- <div class="logo">NotNetflix</div> -->
  <img alt="Vue logo" src="@/assets/logo.png">
</template>
<script>
export default {
  name: "AppIcon",
  data() {
    return {
      publicPath: process.env.BASE_URL
    };
  }
};
</script>
<style scoped>
.logo {
  text-align: center;
  color: var(--color-primary);
  font-size: 40px;
  font-weight: 700;
  margin-bottom: calc(var(--spacing) * 3);
}
</style>
