<template>
  <!--begin::Notice-->
  <div class="notice d-flex rounded bg-light-warning border-warning border border-dashed  p-6">

    <span v-if="type==='warning'" class="svg-icon svg-icon-2tx svg-icon-warning me-4">
            <svg fill="none" height="24" viewBox="0 0 24 24"
                 width="24" xmlns="http://www.w3.org/2000/svg">
                <rect fill="currentColor" height="20" opacity="0.3" rx="10" width="20" x="2" y="2">
                </rect>
                <rect fill="currentColor" height="2" rx="1" transform="rotate(-90 11 14)" width="7" x="11" y="14">
                </rect>
                <rect fill="currentColor" height="2" rx="1" transform="rotate(-90 11 17)" width="2" x="11" y="17">
                </rect>
            </svg>
    </span>


    <div class="d-flex flex-stack flex-grow-1 ">
      <div class=" fw-semibold">
        <h4 class="text-gray-900 fw-bold">{{ title }}</h4>
        <div class="fs-6 text-gray-700 ">{{ message }}
          <a v-if="callToActionText"
             class="fw-bold"
             :href="callToActionLink">{{ callToActionText }}
          </a>.
        </div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Notice",
  props: {
    title: String,
    message: String,
    type: String,
    callToActionLink: String,
    callToActionText: String
  }
};
</script>

<style scoped>

</style>