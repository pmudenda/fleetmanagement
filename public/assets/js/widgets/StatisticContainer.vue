<template>

  <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">

    <div class="d-flex align-items-center">
      <span class="svg-icon svg-icon-3 me-2">
        <img v-if="indicator === 'up'" alt="arrow-up" src="@/assets/media/svg/icons/arrow-up.svg" />
        <img v-if="indicator === 'down'" alt="arrow-down" src="@/assets/media/svg/icons/arrow-down.svg" />
      </span>
      <div class="fs-2 fw-bold counted"
           data-kt-countup="true"
           :data-kt-countup-value="text"
           data-kt-initialized="1">{{text}}
      </div>
    </div>
    <div class="fw-semibold fs-6 text-gray-400">
      {{description}}
    </div>
  </div>
</template>

<script>
export default {
  props: {
    text: String,
    indicator: String,
    description: String
  },
  name: "StatisticContainer"
};
</script>

<style scoped>

</style>