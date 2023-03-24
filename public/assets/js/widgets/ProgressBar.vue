<template>
  <!--begin::Progress-->
  <div class="d-flex align-items-center w-200px w-sm-300px flex-column mt-3">
    <div class="d-flex justify-content-between w-100 mt-auto mb-2">
      <span class="fw-semibold fs-6 text-gray-400">{{trackingText}}</span>
      <span class="fw-bold fs-6">{{percentageComplete}} %</span>
    </div>

    <div class="h-5px mx-3 w-100 bg-light mb-3">
      <div aria-valuemax="100"
           aria-valuemin="0"
           aria-valuenow="50"
           class="bg-success rounded h-5px"
           role="progressbar"
           :style="{'width': percentageComplete +'%'}"></div>
    </div>
  </div>
  <!--end::Progress-->
</template>

<script>
export default {
  name: "ProgressBar",
  props:{
    percentageComplete: Number,
    trackingText: String
  }
};
</script>

<style scoped>

</style>