<script>
export default {
  name: "SiteMap"
};
</script>
<template>
  <!--begin::Modal - Sitemap-->
  <div
    id="kt_app_engage_prebuilts_modal"
    aria-hidden="true"
    class="modal fade bg-dark bg-opacity-75"
    tabindex="-1"
  >
    <!--begin::Modal dialog-->
    <div
      id="kt_engage_prebuilts_modal_dialog"
      class="modal-dialog modal-fullscreen p-5 p-lg-10"
    >
      <!--begin::Modal content-->
      <div class="modal-content rounded-4">
        <!--begin::Modal header-->
        <div
          id="kt_engage_prebuilts_header"
          class="modal-header flex-stack border-0 px-10 pt-5 pb-0"
        >
          <!--begin::View menu-->
          <div id="kt_app_engage_prebuilts_view_menu" class="position-relative z-index-1">
            <!--begin::Toggle-->
            <button
              class="btn btn-flex px-0 rotate-"
              data-kt-element="selected"
              data-kt-menu-offset="0px, -1px"
              data-kt-menu-placement="bottom-start"
              data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
              type="button"
            >
              <!--begin::Title-->
              <span class="fs-6 fw-bold text-gray-600 me-2" data-kt-element="title">
                Image View
              </span>
              <!--end::Title-->

              <!--begin::Arrow-->
              <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
              <span class="svg-icon svg-icon-4 svg-icon-gray-600 rotate-180-"
              ><svg
                fill="none"
                height="24"
                viewBox="0 0 24 24"
                width="24"
                xmlns="http://www.w3.org/2000/svg"
              >
                  <path
                    d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                    fill="currentColor"
                  />
                </svg>
              </span>
              <!--end::Svg Icon-->
              <!--end::Arrow-->
            </button>
            <!--end::Toggle-->

            <!--begin::Menu-->
            <div
              class="menu menu-sub menu-sub-dropdown menu-column menu-active-bg menu-state-primary menu-gray-600 menu-rounded w-150px py-3 px-3"
              data-kt-menu="true"
            >
              <!--begin::Menu item-->
              <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link px-4 py-2 active" data-kt-mode="image" href="#">
                  Image View
                </a>
                <!--end::Menu link-->
              </div>
              <!--end::Menu item-->

              <!--begin::Menu item-->
              <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link px-4 py-2" data-kt-mode="text" href="#">
                  Text View
                </a>
                <!--end::Menu link-->
              </div>
              <!--end::Menu item-->
            </div>
            <!--end::Menu-->
          </div>
          <!--end::View menu-->
          <!--begin::Close-->
          <div
            class="btn btn-sm btn-icon btn-active-color-primary me-n2 position-relative z-index-1"
            data-bs-dismiss="modal"
          >
            <!--begin::Svg Icon | path: icons/duotune/general/gen034.svg-->
            <span class="svg-icon svg-icon-1"
            ><svg
              fill="none"
              height="24"
              viewBox="0 0 24 24"
              width="24"
              xmlns="http://www.w3.org/2000/svg"
            >
                <rect
                  fill="currentColor"
                  height="20"
                  opacity="0.3"
                  rx="5"
                  width="20"
                  x="2"
                  y="2"
                />
                <rect
                  fill="currentColor"
                  height="2"
                  rx="1"
                  transform="rotate(-45 7 15.3137)"
                  width="12"
                  x="7"
                  y="15.3137"
                />
                <rect
                  fill="currentColor"
                  height="2"
                  rx="1"
                  transform="rotate(45 8.41422 7)"
                  width="12"
                  x="8.41422"
                  y="7"
                />
              </svg>
            </span>
            <!--end::Svg Icon-->
          </div>
          <!--end::Close-->
        </div>
        <!--end::Modal header-->

        <!--begin::Modal body-->
        <div id="kt_app_engage_prebuilts_body" class="modal-body pt-0 pb-5 px-15 mt-n5">
          <div class="container-fluid">
            <style>
              .app-prebuilts-thumbnail {
                border: 1px solid var(--kt-body-bg);
                filter: drop-shadow(0px 0px 50px rgba(49, 52, 122, 0.12));
              }
            </style>

            <!--begin::Image view-->
            <div id="kt_app_engage_prebuilts_view_image" class="d-block">
              <!--begin::Tabs wrapper-->
              <div
                id="kt_app_engage_prebuilts_view_image_tabs"
                class="d-flex flex-center"
              >
                <!--begin::Tabs-->
                <ul class="nav nav-tabs border-0 mb-5">
                  <!--begin::Tab item-->
                  <li class="nav-item px-2">
                    <!--begin::Tab link-->
                    <a
                      class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold"
                      data-bs-toggle="tab"
                      href="#kt_app_engage_prebuilts_tab_demos"
                    >
                      Layouts
                    </a>
                    <!--end::Tab link-->
                  </li>
                  <!--end::Tab item-->
                  <!--begin::Tab item-->
                  <li class="nav-item px-2">
                    <!--begin::Tab link-->
                    <a
                      class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold active"
                      data-bs-toggle="tab"
                      href="#kt_app_engage_prebuilts_tab_dashboards"
                    >
                      Dashboards
                    </a>
                    <!--end::Tab link-->
                  </li>
                  <!--end::Tab item-->
                  <!--begin::Tab item-->
                  <li class="nav-item px-2">
                    <!--begin::Tab link-->
                    <a
                      class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold"
                      data-bs-toggle="tab"
                      href="#kt_app_engage_prebuilts_tab_pages"
                    >
                      Pages
                    </a>
                    <!--end::Tab link-->
                  </li>
                  <!--end::Tab item-->
                  <!--begin::Tab item-->
                  <li class="nav-item px-2">
                    <!--begin::Tab link-->
                    <a
                      class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-gray-800 fs-5 fw-bold"
                      data-bs-toggle="tab"
                      href="#kt_app_engage_prebuilts_tab_apps"
                    >
                      Apps
                    </a>
                    <!--end::Tab link-->
                  </li>
                  <!--end::Tab item-->
                </ul>
                <!--end::Tabs-->
              </div>
              <!--end::Tabs wrapper-->

              <!--begin::Tab content-->
              <div class="tab-content">
                <div
                  id="kt_app_engage_prebuilts_tab_demos"
                  class="pt-5 tab-pane fade"
                  role="tabpanel"
                >
                  <!--begin::Scroll wrapper-->
                  <div
                    class="hover-scroll-y pe-12 me-n12"
                    data-kt-scroll="true"
                    data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_image_tabs"
                    data-kt-scroll-height="auto"
                    data-kt-scroll-offset="215px"
                    data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body"
                  >
                    <!--begin::Row-->
                    <div class="row gy-10">
                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Metronic Original
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo1.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            SaaS App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo2.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Sales Tracking App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo30.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Billing SaaS
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo39.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Marketing Automation
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo31.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Databox Dashboard
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo27.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Time Reporting
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo6.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            New Trend
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo3.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Member Dashboard
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo23.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Email Marketing
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo38.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Digital Marketing
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo36.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Project Grid
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo10.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Traffic Analytics
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo35.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Analytics App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo8.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            User Guiding App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo25.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            CRM Software
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo20.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            CRM Dashboard
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo7.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Healthcare Dashboard
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo43.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Delivery Management
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo32.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Calendar Workspace
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo42.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Recruit Automation
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo44.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Social Campaings
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo33.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Cloud Suite
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo37.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Finance Planner
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo11.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Podcast App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo16.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Planable App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo26.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Media Publisher
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo22.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Reports Panel
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo19.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            HR App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo40.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Project Workspace
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo29.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Helpdesk App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo24.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Jobs Site
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo4.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Business Intelligence
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo41.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Goal Tracking
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo18.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Monochrome App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo21.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Finance Analytics
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo34.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Crypto Planner
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo15.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Project Workplace
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo14.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Sales Manager
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo9.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Support Forum
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo5.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Classic Dashboard
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo13.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Data Analyzer
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo12.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo48/index.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Cloud ERP
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo48.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            eCommerce App
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo28.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Events Scheduler
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo17.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            KPI Tracking
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/demos/demo49.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Row-->
                  </div>
                  <!--end::Scroll wrapper-->
                </div>
                <div
                  id="kt_app_engage_prebuilts_tab_dashboards"
                  class="pt-5 tab-pane fade show active"
                  role="tabpanel"
                >
                  <!--begin::Scroll wrapper-->
                  <div
                    class="hover-scroll-y pe-12 me-n12"
                    data-kt-scroll="true"
                    data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_image_tabs"
                    data-kt-scroll-height="auto"
                    data-kt-scroll-offset="215px"
                    data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body"
                  >
                    <!--begin::Row-->
                    <div class="row gy-10">
                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href=""
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Multipurpose
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/multipurpose-demo1.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/marketing.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Marketing
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/marketing.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/social.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Social
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/social.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/ecommerce.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Ecommerce
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/ecommerce.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/store-analytics.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Store-analytics
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/store-analytics.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/logistics.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Logistics
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/logistics.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/delivery.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Delivery
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/delivery.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/online-courses.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Online-courses
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/online-courses.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/school.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            School
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/school.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/crypto.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Crypto
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/crypto.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/finance-performance.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Finance-performance
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/finance-performance.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/website-analytics.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Website-analytics
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/website-analytics.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/bidding.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Bidding
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/bidding.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/podcast.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Podcast
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/podcast.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/projects.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Projects
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/projects.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/call-center.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Call-center
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/call-center.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-md-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/dashboards/pos.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            POS
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/dashboards/pos.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Row-->
                  </div>
                  <!--end::Scroll wrapper-->
                </div>
                <div
                  id="kt_app_engage_prebuilts_tab_apps"
                  class="pt-5 tab-pane fade"
                  role="tabpanel"
                >
                  <!--begin::Scroll wrapper-->
                  <div
                    class="hover-scroll-y pe-12 me-n12"
                    data-kt-scroll="true"
                    data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_image_tabs"
                    data-kt-scroll-height="auto"
                    data-kt-scroll-offset="215px"
                    data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body"
                  >
                    <!--begin::Row-->
                    <div class="row gy-10">
                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/projects/list/list.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Projects
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/projects.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/ecommerce/catalog/products/products.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Ecommerce
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/ecommerce.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/customers/list/list.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Customers
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/customers.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/subscriptions/list/list.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Subscriptions
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/subscriptions.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/user-management/users/list/list.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            User Management
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/user-management.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/invoices/create/main.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Invoices
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/invoices.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/support-center/overview/main.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Support Center
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/support-center.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/chat/private.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Chat
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/chat.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/calendar/calendar.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Calendar
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/calendar.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/file-manager/list/folders.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            File Manager
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/file-manager.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/inbox/listing/listing.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Inbox
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/inbox.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->

                      <!--begin::Col-->
                      <div class="col-lg-4">
                        <!--begin::Preview-->
                        <a
                          class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                          data-kt-href="true"
                          href="demo1/apps/contacts/getting-started.html"
                        >
                          <!--begin::Title-->
                          <h3
                            class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                          >
                            Contacts
                          </h3>
                          <!--end::Title-->

                          <!--begin::Thumbnail-->
                          <span
                            class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                          >
                            <img
                              class="lozad w-100 rounded"
                              data-src="@/assets/media/preview/prebuilts/apps/contacts.png"
                              src="@/assets/media/preview/demos/placeholder.jpg"
                            />
                          </span>
                          <!--end::Thumbnail-->
                        </a>
                        <!--end::Preview-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Row-->
                  </div>
                  <!--end::Scroll wrapper-->
                </div>

                <div id="kt_app_engage_prebuilts_tab_pages" class="tab-pane fade">
                  <!--begin::Tabs wrapper-->
                  <div class="d-flex flex-center mb-5">
                    <div class="border-bottom">
                      <!--begin::Tabs-->
                      <ul
                        class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-6 fw-bold m-0 p-0 gap-4"
                      >
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4 active"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_authentication"
                          >
                            Authentication
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_general"
                          >
                            General
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_account"
                          >
                            Account
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_modals"
                          >
                            Modals
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_wizards"
                          >
                            Wizards
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_search"
                          >
                            Search
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_widgets"
                          >
                            Widgets
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                        <!--begin::Tab item-->
                        <li class="nav-item p-0 m-0">
                          <!--begin::Tab link-->
                          <a
                            class="nav-link text-muted text-active-primary pt-0 pb-4"
                            data-bs-toggle="tab"
                            href="#kt_app_engage_prebuilts_tab_pages_email-templates"
                          >
                            Email Templates
                          </a>
                          <!--end::Tab link-->
                        </li>
                        <!--end::Tab item-->
                      </ul>
                      <!--end::Tabs-->
                    </div>
                  </div>
                  <!--end::Tabs wrapper-->

                  <!--begin::Scroll wrapper-->
                  <div
                    class="hover-scroll-y pe-12 me-n12"
                    data-kt-scroll="true"
                    data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_text_heading"
                    data-kt-scroll-height="auto"
                    data-kt-scroll-offset="250px"
                    data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body, #kt_app_engage_prebuilts_view_image"
                  >
                    <!--begin::Tab content-->
                    <div class="tab-content">
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_authentication"
                        class="tab-pane fade show active"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/layouts/corporate/sign-in.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Corporate
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-corporate.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/layouts/creative/sign-in.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Creative
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-creative.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/layouts/fancy/sign-in.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Fancy
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-fancy.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/layouts/overlay/sign-in.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Overlay
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-overlay.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/extended/multi-steps-sign-up.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Multi-step
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-multistep.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/extended/two-factor-auth.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                2 Factor Auth
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-2factor.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/password-confirmation.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Password Changed
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-passwordchanged.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/verify-email.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Verify Email
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-verifyemail.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/welcome.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Welcome
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-welcome.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/coming-soon.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Coming Soon
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-comingsoon.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/account-deactivated.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Account Deactivated
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-accountdeactivated.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/error-404.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                404 Page
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-404.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/general/error-500.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                505 Page
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/auth-500.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_general"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <div class="mb-5">
                          <!--begin::Collapse toggle-->
                          <a
                            class="btn btn-flex btn-color-dark flex-stack p-0 mb-5 toggle collapsible active"
                            data-bs-toggle="collapse"
                            href="#kt_app_engage_prebuilts_tab_pages_general_user-profile"
                          >
                            <span class="fw-bolder fs-2"> User Profile </span>

                            <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                            <span class="svg-icon toggle-off text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  transform="rotate(-90 10.8891 17.8033)"
                                  width="12"
                                  x="10.8891"
                                  y="17.8033"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.01041"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen036.svg-->
                            <span class="svg-icon toggle-on text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.0104"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </a>
                          <!--end::Collapse toggle-->

                          <!--begin::Collapse content-->
                          <div
                            id="kt_app_engage_prebuilts_tab_pages_general_user-profile"
                            class="show"
                          >
                            <!--begin::Row-->
                            <div class="row g-10 pt-2 pb-5">
                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/overview.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Profile Overview
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-overview.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/projects.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    User Projects
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-overview.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/campaigns.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    User Campaigns
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-campaigns.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/documents.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    User Documents
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-documents.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/followers.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    User Followers
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-followers.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="user-profile/activity.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    User Activity
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/profile-activity.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->
                            </div>
                            <!--end::Row-->
                          </div>
                          <!--end::Collapse content-->
                        </div>
                        <div class="mb-5">
                          <!--begin::Collapse toggle-->
                          <a
                            class="btn btn-flex btn-color-dark flex-stack p-0 mb-5 toggle collapsible collapsed"
                            data-bs-toggle="collapse"
                            href="#kt_app_engage_prebuilts_tab_pages_general_corporate"
                          >
                            <span class="fw-bolder fs-2"> Corporate </span>

                            <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                            <span class="svg-icon toggle-off text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  transform="rotate(-90 10.8891 17.8033)"
                                  width="12"
                                  x="10.8891"
                                  y="17.8033"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.01041"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen036.svg-->
                            <span class="svg-icon toggle-on text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.0104"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </a>
                          <!--end::Collapse toggle-->

                          <!--begin::Collapse content-->
                          <div
                            id="kt_app_engage_prebuilts_tab_pages_general_corporate"
                            class="collapse"
                          >
                            <!--begin::Row-->
                            <div class="row g-10 pt-2 pb-5">
                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="about.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    About Us
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/corporate-aboutus.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="contact.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Contact Us
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/corporate-contactus.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="licenses.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    License
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/corporate-license.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="team.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Our Team
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/corporate-ourteam.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="sitemap.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Sitemap
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/corporate-sitemap.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->
                            </div>
                            <!--end::Row-->
                          </div>
                          <!--end::Collapse content-->
                        </div>
                        <div class="mb-5">
                          <!--begin::Collapse toggle-->
                          <a
                            class="btn btn-flex btn-color-dark flex-stack p-0 mb-5 toggle collapsible collapsed"
                            data-bs-toggle="collapse"
                            href="#kt_app_engage_prebuilts_tab_pages_general_social"
                          >
                            <span class="fw-bolder fs-2"> Social </span>

                            <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                            <span class="svg-icon toggle-off text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  transform="rotate(-90 10.8891 17.8033)"
                                  width="12"
                                  x="10.8891"
                                  y="17.8033"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.01041"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen036.svg-->
                            <span class="svg-icon toggle-on text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.0104"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </a>
                          <!--end::Collapse toggle-->

                          <!--begin::Collapse content-->
                          <div
                            id="kt_app_engage_prebuilts_tab_pages_general_social"
                            class="collapse"
                          >
                            <!--begin::Row-->
                            <div class="row g-10 pt-2 pb-5">
                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="social/feeds.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Activity
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/social-activity.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="social/activity.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Feeds
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/social-feeds.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="social/followers.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Followers
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/social-followers.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="social/settings.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Settings
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/social-settings.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->
                            </div>
                            <!--end::Row-->
                          </div>
                          <!--end::Collapse content-->
                        </div>
                        <div class="mb-5">
                          <!--begin::Collapse toggle-->
                          <a
                            class="btn btn-flex btn-color-dark flex-stack p-0 mb-5 toggle collapsible collapsed"
                            data-bs-toggle="collapse"
                            href="#kt_app_engage_prebuilts_tab_pages_general_others"
                          >
                            <span class="fw-bolder fs-2"> Others </span>

                            <!--begin::Svg Icon | path: icons/duotune/general/gen035.svg-->
                            <span class="svg-icon toggle-off text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  transform="rotate(-90 10.8891 17.8033)"
                                  width="12"
                                  x="10.8891"
                                  y="17.8033"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.01041"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                            <!--begin::Svg Icon | path: icons/duotune/general/gen036.svg-->
                            <span class="svg-icon toggle-on text-primary svg-icon-2 ms-4"
                            ><svg
                              fill="none"
                              height="24"
                              viewBox="0 0 24 24"
                              width="24"
                              xmlns="http://www.w3.org/2000/svg"
                            >
                                <rect
                                  fill="currentColor"
                                  height="20"
                                  opacity="0.3"
                                  rx="5"
                                  width="20"
                                  x="2"
                                  y="2"
                                />
                                <rect
                                  fill="currentColor"
                                  height="2"
                                  rx="1"
                                  width="12"
                                  x="6.0104"
                                  y="10.9247"
                                />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </a>
                          <!--end::Collapse toggle-->

                          <!--begin::Collapse content-->
                          <div
                            id="kt_app_engage_prebuilts_tab_pages_general_others"
                            class="collapse"
                          >
                            <!--begin::Row-->
                            <div class="row g-10 pt-2 pb-5">
                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="faq/classic.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    FAQ Classic
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/faq-classic.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="faq/extended.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    FAQ Extended
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/faq-extended.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="blog/home.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Blog Home
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/blog-home.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->

                              <!--begin::Col-->
                              <div class="col-lg-4">
                                <!--begin::Preview-->
                                <a
                                  class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                                  data-kt-href="true"
                                  href="blog/post.html"
                                >
                                  <!--begin::Title-->
                                  <h3
                                    class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                                  >
                                    Blog Post
                                  </h3>
                                  <!--end::Title-->

                                  <!--begin::Thumbnail-->
                                  <span
                                    class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                                  >
                                    <img
                                      class="lozad w-100 rounded"
                                      data-src="@/assets/media/preview/prebuilts/pages/blog-post.png"
                                      src="@/assets/media/preview/demos/placeholder.jpg"
                                    />
                                  </span>
                                  <!--end::Thumbnail-->
                                </a>
                                <!--end::Preview-->
                              </div>
                              <!--end::Col-->
                            </div>
                            <!--end::Row-->
                          </div>
                          <!--end::Collapse content-->
                        </div>
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_account"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/overview.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Overview
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-overview.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/settings.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Settings
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-settings.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/billing.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Billing
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-billing.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/security.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Security
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-security.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/referrals.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Referrals
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-referrals.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/logs.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Logs
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-logs.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/api-keys.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                API Keys
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-apikeys.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/statements.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Statements
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-statements.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/account/billing.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Billing
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/account-billing.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_modals"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/general/view-users.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                View Friends
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-viewfriends.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/general/upgrade-plan.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Upgrade Plan
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-upgradeplan.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/top-up-wallet.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Topup Wallet
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-topupwallet.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/general/share-earn.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Share & Earn
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-shareandearn.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/general/select-users.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Select User
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-selectuser.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/forms/bidding.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Place Bid
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-placeyourbid.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/offer-a-deal.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Offer Deal
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-offeradeal.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/forms/new-target.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                New Target
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-newtarget.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/forms/new-card.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                New Card
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-newcard.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/forms/new-address.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                New Address
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-newaddress.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/general/invite-friends.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Invite Friend
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-invitefriend.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/create-project.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Create Project
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-createproject.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/create-campaign.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Create Campaign
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-createcampaign.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/create-account.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Create Business Account
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-createbusinessacc.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/create-app.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Create App
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-createapp.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/forms/create-api-key.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Create Api Key
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-createapikey.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/modals/wizards/two-factor-authentication.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                2 Factor Auth
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/modal-2factorauth.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_wizards"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/wizards/horizontal.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                2 Factor Auth
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/wizard-2factorauth.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/wizards/horizontal.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Horizontal
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/wizard-horizontal.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/wizards/vertical.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Vertical
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/wizard-vertical.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_search"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/search/users.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Search Users
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/search-users.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/search/horizontal.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Search Horizontal
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/search-horizontal.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/search/vertical.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Search Vertical
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/search-vertical.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/utilities/search/select-location.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Search Location
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/search-location.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_widgets"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/charts.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Charts
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-charts.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/feeds.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Feeds
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-feeds.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/lists.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Lists
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-lists.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/mixed.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Mixed
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-mixed.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/statistics.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Statistics
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-stats.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/widgets/tables.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Tables
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/widgets-tables.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                      <div
                        id="kt_app_engage_prebuilts_tab_pages_email-templates"
                        class="tab-pane fade"
                        role="tabpanel"
                      >
                        <!--begin::Row-->
                        <div class="row g-10">
                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/welcome-message.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Welcome
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-welcome.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/subscription-confirmed.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Subscription Confirmed
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-subscriptionconfirmed.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/reset-password.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Reset Password
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-resetpassword.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/card-declined.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Card Declined
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-creditcarddeclined.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/promo-1.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Promotion 1
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-promo1.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/promo-2.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Promotion 2
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-promo2.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->

                          <!--begin::Col-->
                          <div class="col-lg-4">
                            <!--begin::Preview-->
                            <a
                              class="preview-thumbnail bg-light border d-flex flex-column rounded-3 hover-elevate-up overflow-hidden"
                              data-kt-href="true"
                              href="demo1/authentication/email/promo-3.html"
                            >
                              <!--begin::Title-->
                              <h3
                                class="ps-12 pt-9 mb-7 text-gray-900 fw-bold letter-spacing fs-4"
                              >
                                Promotion 3
                              </h3>
                              <!--end::Title-->

                              <!--begin::Thumbnail-->
                              <span
                                class="app-prebuilts-thumbnail rounded ms-12 mb-n6 mb-lg-n15 me-n6 mh-350px overflow-hidden"
                              >
                                <img
                                  class="lozad w-100 rounded"
                                  data-src="@/assets/media/preview/prebuilts/pages/email-promo3.png"
                                  src="@/assets/media/preview/demos/placeholder.jpg"
                                />
                              </span>
                              <!--end::Thumbnail-->
                            </a>
                            <!--end::Preview-->
                          </div>
                          <!--end::Col-->
                        </div>
                        <!--end::Row-->
                      </div>
                    </div>
                    <!--end::Tab content-->
                  </div>
                  <!--end::Scroll wrapper-->
                </div>
              </div>
              <!--end::Tab content-->
            </div>
            <!--end::Image view-->
            <!--begin::Text view-->
            <div id="kt_app_engage_prebuilts_view_text" class="pt-2 d-none">
              <!--begin::Heading-->
              <h1
                id="kt_app_engage_prebuilts_view_text_heading"
                class="fs-2x text-dark fw-bolder text-center mb-4"
              >
                Sitemap
              </h1>
              <!--end::Heading-->

              <!--begin::Wrapper-->
              <div
                id="kt_app_engage_prebuilts_view_text_heading"
                class="hover-scroll-y pe-12 me-n12"
                data-kt-scroll="true"
                data-kt-scroll-dependencies="#kt_app_engage_prebuilts_header, #kt_app_engage_prebuilts_view_text_heading"
                data-kt-scroll-height="auto"
                data-kt-scroll-offset="190px"
                data-kt-scroll-wrappers="#kt_app_engage_prebuilts_modal, #kt_app_engage_prebuilts_modal_dialog, #kt_app_engage_prebuilts_body, #kt_app_engage_prebuilts_view_text"
              >
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Layouts</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Metronic Original
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          SaaS App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Sales Tracking App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Billing SaaS
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Marketing Automation
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Databox Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Time Reporting
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          New Trend
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Member Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Email Marketing
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Digital Marketing
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Project Grid
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Traffic Analytics
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Analytics App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          User Guiding App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          CRM Software
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          CRM Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Healthcare Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Delivery Management
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Calendar Workspace
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Recruit Automation
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Social Campaings
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Cloud Suite
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Finance Planner
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Podcast App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Planable App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Media Publisher
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Reports Panel
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          HR App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Project Workspace
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Helpdesk App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Jobs Site
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Business Intelligence
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Goal Tracking
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Monochrome App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Finance Analytics
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Crypto Planner
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Project Workplace
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Sales Manager
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Support Forum
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Classic Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Data Analyzer
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo48/index.html"
                        >
                          Cloud ERP
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          eCommerce App
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Events Scheduler
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          KPI Tracking
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->

                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Dashboards</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href=""
                        >
                          Multipurpose
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/marketing.html"
                        >
                          Marketing Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/social.html"
                        >
                          Social Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/ecommerce.html"
                        >
                          eCommerce Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/store-analytics.html"
                        >
                          Store Analytics
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/logistics.html"
                        >
                          Logistics
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/delivery.html"
                        >
                          Delivery
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/online-courses.html"
                        >
                          Online Courses
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/school.html"
                        >
                          Hello Tyler
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/crypto.html"
                        >
                          My Balance: 37,045$
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/finance-performance.html"
                        >
                          Finance Performance
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/website-analytics.html"
                        >
                          Website Analytics
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/bidding.html"
                        >
                          Bidding Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/podcast.html"
                        >
                          Podcast Dashboard
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/projects.html"
                        >
                          Projects Dashboard
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/call-center.html"
                        >
                          Call Center
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/dashboards/pos.html"
                        >
                          POS System
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->

                <!--begin::Section-->
                <div class="mb-10 mb-lg-17"></div>
                <!--end::Section-->

                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Authentication</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/layouts/corporate/sign-in.html"
                        >
                          Corporate
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/layouts/creative/sign-in.html"
                        >
                          Creative
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/layouts/fancy/sign-in.html"
                        >
                          Fancy
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/layouts/overlay/sign-in.html"
                        >
                          Overlay
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/extended/multi-steps-sign-up.html"
                        >
                          Multi-step
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/extended/two-factor-auth.html"
                        >
                          2 Factor Auth
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/password-confirmation.html"
                        >
                          Password Changed
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/verify-email.html"
                        >
                          Verify Email
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/welcome.html"
                        >
                          Welcome
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/coming-soon.html"
                        >
                          Coming Soon
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/account-deactivated.html"
                        >
                          Account Deactivated
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/error-404.html"
                        >
                          404 Page
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/general/error-500.html"
                        >
                          505 Page
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Account</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/overview.html"
                        >
                          Overview
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/settings.html"
                        >
                          Settings
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/billing.html"
                        >
                          Billing
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/security.html"
                        >
                          Security
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/referrals.html"
                        >
                          Referrals
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/logs.html"
                        >
                          Logs
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/api-keys.html"
                        >
                          API Keys
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/statements.html"
                        >
                          Statements
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/account/billing.html"
                        >
                          Billing
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Modals</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/general/view-users.html"
                        >
                          View Friends
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/general/upgrade-plan.html"
                        >
                          Upgrade Plan
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/top-up-wallet.html"
                        >
                          Topup Wallet
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/general/share-earn.html"
                        >
                          Share & Earn
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/general/select-users.html"
                        >
                          Select User
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/forms/bidding.html"
                        >
                          Place Bid
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/offer-a-deal.html"
                        >
                          Offer Deal
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/forms/new-target.html"
                        >
                          New Target
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/forms/new-card.html"
                        >
                          New Card
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/forms/new-address.html"
                        >
                          New Address
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/general/invite-friends.html"
                        >
                          Invite Friend
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/create-project.html"
                        >
                          Create Project
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/create-campaign.html"
                        >
                          Create Campaign
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/create-account.html"
                        >
                          Create Business Account
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/create-app.html"
                        >
                          Create App
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/forms/create-api-key.html"
                        >
                          Create Api Key
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/modals/wizards/two-factor-authentication.html"
                        >
                          2 Factor Auth
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Wizards</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/wizards/horizontal.html"
                        >
                          2 Factor Auth
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/wizards/horizontal.html"
                        >
                          Horizontal
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/wizards/vertical.html"
                        >
                          Vertical
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Search</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/search/users.html"
                        >
                          Search Users
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/search/horizontal.html"
                        >
                          Search Horizontal
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/search/vertical.html"
                        >
                          Search Vertical
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/utilities/search/select-location.html"
                        >
                          Search Location
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Widgets</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/charts.html"
                        >
                          Charts
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/feeds.html"
                        >
                          Feeds
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/lists.html"
                        >
                          Lists
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/mixed.html"
                        >
                          Mixed
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/statistics.html"
                        >
                          Statistics
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/widgets/tables.html"
                        >
                          Tables
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - Email Templates</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/welcome-message.html"
                        >
                          Welcome
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/subscription-confirmed.html"
                        >
                          Subscription Confirmed
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/reset-password.html"
                        >
                          Reset Password
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/card-declined.html"
                        >
                          Card Declined
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/promo-1.html"
                        >
                          Promotion 1
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/promo-2.html"
                        >
                          Promotion 2
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/authentication/email/promo-3.html"
                        >
                          Promotion 3
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - General - User Profile</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="user-profile/overview.html">
                          Profile Overview
                        </a>

                        <a class="fw-6 fw-semibold" href="user-profile/projects.html">
                          User Projects
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="user-profile/campaigns.html">
                          User Campaigns
                        </a>

                        <a class="fw-6 fw-semibold" href="user-profile/documents.html">
                          User Documents
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="user-profile/followers.html">
                          User Followers
                        </a>

                        <a class="fw-6 fw-semibold" href="user-profile/activity.html">
                          User Activity
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - General - Corporate</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="about.html"> About Us </a>

                        <a class="fw-6 fw-semibold" href="contact.html"> Contact Us </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="licenses.html"> License </a>

                        <a class="fw-6 fw-semibold" href="team.html"> Our Team </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="sitemap.html"> Sitemap </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - General - Social</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="social/feeds.html">
                          Activity
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="social/activity.html">
                          Feeds
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="social/followers.html">
                          Followers
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="social/settings.html">
                          Settings
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
                <!--begin::Section-->
                <div class="mb-10 mb-lg-17">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Pages - General - Others</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="faq/classic.html">
                          FAQ Classic
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="faq/extended.html">
                          FAQ Extended
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="blog/home.html"> Blog Home </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a class="fw-6 fw-semibold" href="blog/post.html"> Blog Post </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->

                <!--begin::Section-->
                <div class="">
                  <!--begin::Title-->
                  <h3 class="text-dark fw-bolder mb-7">Apps</h3>
                  <!--end::Title-->

                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/projects/list/list.html"
                        >
                          Projects
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/ecommerce/catalog/products/products.html"
                        >
                          Ecommerce
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/customers/list/list.html"
                        >
                          Customers
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/subscriptions/list/list.html"
                        >
                          Subscriptions
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/user-management/users/list/list.html"
                        >
                          User Management
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/invoices/create/main.html"
                        >
                          Invoices
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/support-center/overview/main.html"
                        >
                          Support Center
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/chat/private.html"
                        >
                          Chat
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/calendar/calendar.html"
                        >
                          Calendar
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->

                    <!--begin::Col-->
                    <div class="col-lg-3">
                      <!--begin::List-->
                      <div class="d-flex flex-column gap-3 gap-lg-5">
                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/file-manager/list/folders.html"
                        >
                          File Manager
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/inbox/listing/listing.html"
                        >
                          Inbox
                        </a>

                        <a
                          class="fw-6 fw-semibold"
                          href="demo1/apps/contacts/getting-started.html"
                        >
                          Contacts
                        </a>
                      </div>
                      <!--end::List-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Section-->
              </div>
              <!--end::Wrapper-->
            </div>
            <!--end::Text view--->
          </div>
        </div>
        <!--end::Modal body-->
      </div>
      <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
  </div>
  <!--end::Modal - Sitemap-->
</template>
