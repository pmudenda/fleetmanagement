<script>
export default {
  name: "AppLayoutBuilder"
};
</script>

<template>
  <div
    id="kt_app_layout_builder"
    class="bg-body"
    data-kt-drawer="true"
    data-kt-drawer-activate="true"
    data-kt-drawer-close="#kt_app_layout_builder_close"
    data-kt-drawer-direction="end"
    data-kt-drawer-name="app-settings"
    data-kt-drawer-overlay="true"
    data-kt-drawer-toggle="#kt_app_layout_builder_toggle"
    data-kt-drawer-width="{default:'300px', 'lg': '380px'}"
  >
    <!--begin::Card-->
    <div class="card border-0 shadow-none rounded-0 w-100">
      <!--begin::Card header-->
      <div
        id="kt_app_layout_builder_header"
        class="card-header bgi-position-y-bottom bgi-position-x-end bgi-size-cover bgi-no-repeat rounded-0 border-0 py-4"
        style="background-image: url('@/assets/media/misc/layout/header-bg.jpg')"
      >
        <!--begin::Card title-->
        <h3 class="card-title fs-3 fw-bold text-white flex-column m-0">
          Metronic Builder
          <small class="text-white opacity-50 fs-7 fw-semibold pt-1"
          >Get your product deeply customized</small
          >
        </h3>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <button
            id="kt_app_layout_builder_close"
            class="btn btn-sm btn-icon bg-white bg-opacity-25 btn-color-white p-0 w-20px h-20px rounded-1"
            type="button"
          >
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr061.svg-->
            <span class="svg-icon svg-icon-3"
            ><svg
              fill="none"
              height="24"
              viewBox="0 0 24 24"
              width="24"
              xmlns="http://www.w3.org/2000/svg"
            >
                <rect
                  fill="currentColor"
                  height="2"
                  opacity="0.5"
                  rx="1"
                  transform="rotate(-45 6 17.3137)"
                  width="16"
                  x="6"
                  y="17.3137"
                />
                <rect
                  fill="currentColor"
                  height="2"
                  rx="1"
                  transform="rotate(45 7.41422 6)"
                  width="16"
                  x="7.41422"
                  y="6"
                />
              </svg>
            </span>
            <!--end::Svg Icon-->
          </button>
        </div>
        <!--end::Card toolbar-->
      </div>
      <!--end::Card header-->
      <!--begin::Card body-->
      <div id="kt_app_layout_builder_body" class="card-body position-relative">
        <!--begin::Content-->
        <div
          id="kt_app_settings_content"
          class="position-relative scroll-y me-n5 pe-5"
          data-kt-scroll="true"
          data-kt-scroll-dependencies="#kt_app_layout_builder_header, #kt_app_layout_builder_footer"
          data-kt-scroll-height="auto"
          data-kt-scroll-offset="5px"
          data-kt-scroll-wrappers="#kt_app_layout_builder_body"
        >
          <!--begin::Form-->
          <form
            id="kt_app_layout_builder_form"
            action=""
            class="form"
            method="POST"
          >
            <input
              id="kt_app_layout_builder_action"
              name="layout-builder[action]"
              type="hidden"
            />
            <!--begin::Card body-->
            <div class="card-body p-0">
              <!--begin::Form group-->
              <div class="form-group">
                <!--begin::Heading-->
                <div class="mb-6">
                  <h4 class="fw-bold text-dark">Theme Mode</h4>
                  <div class="fw-semibold text-muted fs-7 d-block lh-1">
                    Enjoy Dark & Light modes.

                    <a
                      class="fw-semibold"
                      href=""
                      target="_blank"
                    >See docs</a
                    >
                  </div>
                </div>
                <!--end::Heading-->

                <!--begin::Options-->
                <div
                  class="row"
                  data-kt-buttons="true"
                  data-kt-buttons-target=".form-check-image,.form-check-input"
                >
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/preview/demos/demo1/light-ltr.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-solid form-check-sm form-check-success"
                      >
                        <input
                          id="kt_layout_builder_theme_mode_light"
                          class="form-check-input"
                          name="theme_mode"
                          type="radio"
                          value="light"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Light</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/preview/demos/demo1/dark-ltr.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-solid form-check-sm form-check-success"
                      >
                        <input
                          id="kt_layout_builder_theme_mode_dark"
                          class="form-check-input"
                          name="theme_mode"
                          type="radio"
                          value="dark"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Dark</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Options-->
              </div>
              <!--end::Form group-->

              <!--begin::Separator-->
              <div class="separator separator-dashed my-5"></div>
              <!--end::Separator-->

              <!--begin::Form group-->
              <div class="form-group d-flex flex-stack">
                <!--begin::Heading-->
                <div class="d-flex flex-column">
                  <h4 class="fw-bold text-dark">RTL Mode</h4>
                  <div class="fs-7 fw-semibold text-muted">
                    Change Language Direction.

                    <a
                      class="fw-semibold"
                      href=""
                      target="_blank"
                    >See docs</a
                    >
                  </div>
                </div>
                <!--end::Heading-->

                <!--begin::Option-->
                <div class="d-flex justify-content-end">
                  <!--begin::Check-->
                  <div
                    class="form-check form-check-custom form-check-solid form-check-success form-switch"
                  >
                    <input
                      name="layout-builder[layout][app][general][rtl]"
                      type="hidden"
                      value="false"
                    />
                    <input
                      id="kt_builder_rtl"
                      class="form-check-input w-45px h-30px"
                      name="layout-builder[layout][app][general][rtl]"
                      type="checkbox"
                      value="true"
                    />

                    <!--begin::Label-->
                    <label class="form-check-label" for="kt_builder_rtl"></label>
                    <!--end::Label-->
                  </div>
                  <!--end::Check-->
                </div>
                <!--end::Option-->
              </div>
              <!--end::Form group-->

              <!--begin::Separator-->
              <div class="separator separator-dashed my-5"></div>
              <!--end::Separator-->

              <!--begin::Form group-->
              <div class="form-group d-flex flex-stack">
                <!--begin::Heading-->
                <div class="d-flex flex-column">
                  <h4 class="fw-bold text-dark">Boxed Mode</h4>
                  <div class="fs-7 fw-semibold text-muted">Set page boxed mode.</div>
                </div>
                <!--end::Heading-->

                <!--begin::Option-->
                <div class="d-flex justify-content-end">
                  <!--begin::Check-->
                  <div
                    class="form-check form-check-custom form-check-solid form-check-success form-switch"
                  >
                    <input
                      name="layout-builder[layout][app][general][page-boxed]"
                      type="hidden"
                      value="false"
                    />
                    <input
                      id="kt_builder_general_page_boxed"
                      class="form-check-input w-45px h-30px"
                      name="layout-builder[layout][app][general][page-boxed]"
                      type="checkbox"
                      value="true"
                    />

                    <!--begin::Label-->
                    <label
                      class="form-check-label"
                      for="kt_builder_general_page_boxed"
                    ></label>
                    <!--end::Label-->
                  </div>
                  <!--end::Check-->
                </div>
                <!--end::Option-->
              </div>
              <!--end::Form group-->

              <!--begin::Separator-->
              <div class="separator separator-dashed my-5"></div>
              <!--end::Separator-->

              <!--begin::Form group-->
              <div class="form-group">
                <!--begin::Heading-->
                <div class="d-flex flex-column mb-4">
                  <h4 class="fw-bold text-dark">Menu Icon</h4>
                  <div class="fs-7 fw-semibold text-muted">Sidebar menu icon options</div>
                </div>
                <!--end::Heading-->

                <!--begin::Options-->
                <div class="d-flex">
                  <!--begin::Check-->
                  <div
                    class="form-check form-check-custom form-check-success form-check-solid form-check-sm me-5"
                  >
                    <input
                      id="kt_builder_icon_svg"
                      checked
                      class="form-check-input"
                      name="layout-builder[layout][app][sidebar][default][menu][icon-type]"
                      type="radio"
                      value="svg"
                    />

                    <!--begin::Label-->
                    <label
                      class="form-check-label text-gray-700 fw-bold text-nowrap"
                      for="kt_builder_icon_svg"
                    >
                      SVG Duotone
                    </label>
                    <!--end::Label-->
                  </div>
                  <!--end::Check-->
                  <!--begin::Check-->
                  <div
                    class="form-check form-check-custom form-check-success form-check-solid form-check-sm me-5"
                  >
                    <input
                      id="kt_builder_icon_font"
                      class="form-check-input"
                      name="layout-builder[layout][app][sidebar][default][menu][icon-type]"
                      type="radio"
                      value="font"
                    />

                    <!--begin::Label-->
                    <label
                      class="form-check-label text-gray-700 fw-bold text-nowrap"
                      for="kt_builder_icon_font"
                    >
                      Font Icons
                    </label>
                    <!--end::Label-->
                  </div>
                  <!--end::Check-->
                </div>
                <!--end::Options-->
              </div>
              <!--end::Form group-->

              <!--begin::Separator-->
              <div class="separator separator-dashed my-5"></div>
              <!--end::Separator-->

              <!--begin::Form group-->
              <div class="form-group">
                <!--begin::Heading-->
                <div class="mb-6">
                  <h4 class="fw-bold text-dark">Layouts</h4>
                  <span class="fw-semibold text-muted fs-7 lh-1"> 4 main layouts. </span>
                  <a
                    class="fw-semibold text-primary"
                    href=""
                  >More layout options</a
                  >
                </div>
                <!--end::Heading-->

                <!--begin::Options-->
                <div
                  class="row gy-3"
                  data-kt-buttons="true"
                  data-kt-buttons-target=".form-check-image:not(.disabled),.form-check-input:not([disabled])"
                >
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success active active">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/misc/layout/dark-sidebar.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-success form-check-sm form-check-solid"
                      >
                        <input
                          checked
                          class="form-check-input"
                          name="layout-builder[layout][app][general][layout]"
                          type="radio"
                          value="dark-sidebar"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Dark Sidebar</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/misc/layout/light-sidebar.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-success form-check-sm form-check-solid"
                      >
                        <input
                          class="form-check-input"
                          name="layout-builder[layout][app][general][layout]"
                          type="radio"
                          value="light-sidebar"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Light Sidebar</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/misc/layout/dark-header.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-success form-check-sm form-check-solid"
                      >
                        <input
                          class="form-check-input"
                          name="layout-builder[layout][app][general][layout]"
                          type="radio"
                          value="dark-header"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Dark Header</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                  <!--begin::Col-->
                  <div class="col-6">
                    <!--begin::Option-->
                    <label class="form-check-image form-check-success">
                      <!--begin::Image-->
                      <div class="form-check-wrapper">
                        <img
                          alt=""
                          class="mw-100"
                          src="@/assets/media/misc/layout/light-header.png"
                        />
                      </div>
                      <!--end::Image-->

                      <!--begin::Check-->
                      <div
                        class="form-check form-check-custom form-check-success form-check-sm form-check-solid"
                      >
                        <input
                          class="form-check-input"
                          name="layout-builder[layout][app][general][layout]"
                          type="radio"
                          value="light-header"
                        />

                        <!--begin::Label-->
                        <div class="form-check-label text-gray-700">Light Header</div>
                        <!--end::Label-->
                      </div>
                      <!--end::Check-->
                    </label>
                    <!--end::Option-->
                  </div>
                  <!--end::Col-->
                </div>
                <!--end::Options-->
              </div>
              <!--end::Form group-->
            </div>
            <!--end::Card body-->
          </form>
          <!--end::Form-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::Card body-->

      <!--begin::Card footer-->
      <div
        id="kt_app_layout_builder_footer"
        class="card-footer border-0 d-flex gap-3 pb-9 pt-0"
      >
        <button
          id="kt_app_layout_builder_preview"
          class="btn btn-primary flex-grow-1 fw-semibold"
          type="button"
        >
          <!--begin::Indicator label-->
          <span class="indicator-label"> Preview</span>
          <!--end::Indicator label-->

          <!--begin::Indicator progress-->
          <span class="indicator-progress">
            Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
          </span>
          <!--end::Indicator progress-->
        </button>

        <button
          id="kt_app_layout_builder_reset"
          class="btn btn-light flex-grow-1 fw-semibold"
          type="button"
        >
          <!--begin::Indicator label-->
          <span class="indicator-label"> Reset</span>
          <!--end::Indicator label-->

          <!--begin::Indicator progress-->
          <span class="indicator-progress">
            Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
          </span>
          <!--end::Indicator progress-->
        </button>
      </div>
      <!--end::Card footer-->
    </div>
    <!--end::Card-->
  </div>
</template>
