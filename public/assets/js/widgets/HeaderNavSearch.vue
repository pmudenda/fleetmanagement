<template>
  <!--begin::Search-->
  <div class="app-navbar-item align-items-stretch ms-1 ms-md-3">
    <!--begin::Search-->
    <div id="kt_header_search" class="header-search d-flex align-items-stretch" data-kt-menu-overflow="false"
         data-kt-menu-permanent="true" data-kt-menu-placement="bottom-end" data-kt-menu-trigger="auto"
         data-kt-search-enter="enter" data-kt-search-keypress="true" data-kt-search-layout="menu"
         data-kt-search-min-length="2">
      <!--begin::Search toggle-->
      <div id="kt_header_search_toggle" class="d-flex align-items-center" data-kt-search-element="toggle">
        <div
          class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-30px h-30px w-md-40px h-md-40px">
          <!--begin::Svg Icon | path: icons/duotune/general/gen021.svg-->
          <span class="svg-icon svg-icon-2 svg-icon-md-1">
            <svg fill="none" height="24" viewBox="0 0 24 24"
                 width="24" xmlns="http://www.w3.org/2000/svg">
                      <rect fill="currentColor" height="2" opacity="0.5" rx="1" transform="rotate(45 17.0365 15.1223)" width="8.15546"
                            x="17.0365" y="15.1223" />
                      <path
                        d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                        fill="currentColor" />
                    </svg>
                  </span>
          <!--end::Svg Icon-->
        </div>
      </div>
      <!--end::Search toggle-->

      <!--begin::Menu-->
      <div class="menu menu-sub menu-sub-dropdown p-7 w-325px w-md-375px" data-kt-search-element="content">
        <!--begin::Wrapper-->
        <div data-kt-search-element="wrapper">
          <!--begin::Form-->
          <form autocomplete="off" class="w-100 position-relative mb-3" data-kt-search-element="form">
            <!--begin::Icon-->
            <!--begin::Svg Icon-->
            <span
              class="svg-icon svg-icon-2 svg-icon-lg-1 svg-icon-gray-500 position-absolute top-50 translate-middle-y ms-0">
              <svg fill="none"
                   height="24"
                   viewBox="0 0 24 24"
                   width="24"
                   xmlns="http://www.w3.org/2000/svg">
                        <rect fill="currentColor" height="2" opacity="0.5" rx="1" transform="rotate(45 17.0365 15.1223)" width="8.15546"
                              x="17.0365" y="15.1223" />
                        <path
                          d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                          fill="currentColor" />
                      </svg>
                    </span>
            <!--end::Svg Icon-->
            <!--end::Icon-->

            <!--begin::Input-->
            <input class="search-input form-control form-control-flush ps-10" data-kt-search-element="input" name="search" placeholder="Search..."
                   type="text" value="" />
            <!--end::Input-->

            <!--begin::Spinner-->
            <span class="search-spinner position-absolute top-50 end-0 translate-middle-y lh-0 d-none me-1"
                  data-kt-search-element="spinner">
                      <span class="spinner-border h-15px w-15px align-middle text-gray-400"></span>
            </span>
            <!--end::Spinner-->

            <!--begin::Reset-->
            <span
              class="search-reset btn btn-flush btn-active-color-primary position-absolute top-50 end-0 translate-middle-y lh-0 d-none"
              data-kt-search-element="clear">
                      <!--begin::Svg Icon-->
                      <span class="svg-icon svg-icon-2 svg-icon-lg-1 me-0">
                        <svg fill="none"
                             height="24"
                             viewBox="0 0 24 24"
                             width="24"
                             xmlns="http://www.w3.org/2000/svg">
                          <rect fill="currentColor" height="2" opacity="0.5" rx="1" transform="rotate(-45 6 17.3137)" width="16"
                                x="6" y="17.3137" />
                          <rect fill="currentColor" height="2" rx="1" transform="rotate(45 7.41422 6)" width="16" x="7.41422"
                                y="6" />
                        </svg>
                      </span>
              <!--end::Svg Icon-->
                    </span>
            <!--end::Reset-->

            <!--begin::Toolbar-->
            <div class="position-absolute top-50 end-0 translate-middle-y"
                 data-kt-search-element="toolbar">
              <!--begin::Preferences toggle-->
              <div class="btn btn-icon w-20px btn-sm btn-active-color-primary me-1"
                   data-bs-toggle="tooltip"
                   data-kt-search-element="preferences-show"
                   title="Show search preferences">
                <!--begin::Svg Icon-->
                <span class="svg-icon svg-icon-1">
                  <svg fill="none" height="24"
                       viewBox="0 0 24 24"
                       width="24"
                       xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.1 11.5V12.6C22.1 13.2 21.7 13.6 21.2 13.7L19.9 13.9C19.7 14.7 19.4 15.5 18.9 16.2L19.7 17.2999C20 17.6999 20 18.3999 19.6 18.7999L18.8 19.6C18.4 20 17.8 20 17.3 19.7L16.2 18.9C15.5 19.3 14.7 19.7 13.9 19.9L13.7 21.2C13.6 21.7 13.1 22.1 12.6 22.1H11.5C10.9 22.1 10.5 21.7 10.4 21.2L10.2 19.9C9.4 19.7 8.6 19.4 7.9 18.9L6.8 19.7C6.4 20 5.7 20 5.3 19.6L4.5 18.7999C4.1 18.3999 4.1 17.7999 4.4 17.2999L5.2 16.2C4.8 15.5 4.4 14.7 4.2 13.9L2.9 13.7C2.4 13.6 2 13.1 2 12.6V11.5C2 10.9 2.4 10.5 2.9 10.4L4.2 10.2C4.4 9.39995 4.7 8.60002 5.2 7.90002L4.4 6.79993C4.1 6.39993 4.1 5.69993 4.5 5.29993L5.3 4.5C5.7 4.1 6.3 4.10002 6.8 4.40002L7.9 5.19995C8.6 4.79995 9.4 4.39995 10.2 4.19995L10.4 2.90002C10.5 2.40002 11 2 11.5 2H12.6C13.2 2 13.6 2.40002 13.7 2.90002L13.9 4.19995C14.7 4.39995 15.5 4.69995 16.2 5.19995L17.3 4.40002C17.7 4.10002 18.4 4.1 18.8 4.5L19.6 5.29993C20 5.69993 20 6.29993 19.7 6.79993L18.9 7.90002C19.3 8.60002 19.7 9.39995 19.9 10.2L21.2 10.4C21.7 10.5 22.1 11 22.1 11.5ZM12.1 8.59998C10.2 8.59998 8.6 10.2 8.6 12.1C8.6 14 10.2 15.6 12.1 15.6C14 15.6 15.6 14 15.6 12.1C15.6 10.2 14 8.59998 12.1 8.59998Z"
                                  fill="currentColor"
                                  opacity="0.3" />
                            <path
                              d="M17.1 12.1C17.1 14.9 14.9 17.1 12.1 17.1C9.30001 17.1 7.10001 14.9 7.10001 12.1C7.10001 9.29998 9.30001 7.09998 12.1 7.09998C14.9 7.09998 17.1 9.29998 17.1 12.1ZM12.1 10.1C11 10.1 10.1 11 10.1 12.1C10.1 13.2 11 14.1 12.1 14.1C13.2 14.1 14.1 13.2 14.1 12.1C14.1 11 13.2 10.1 12.1 10.1Z"
                              fill="currentColor" />
                  </svg>
                </span>
                <!--end::Svg Icon-->
              </div>
              <!--end::Preferences toggle-->

              <!--begin::Advanced search toggle-->
              <div class="btn btn-icon w-20px btn-sm btn-active-color-primary"
                   data-bs-toggle="tooltip" data-kt-search-element="advanced-options-form-show"
                   title="Show more search options">
                <!--begin::Svg Icon-->
                <span class="svg-icon svg-icon-2">
                  <svg fill="none" height="24"
                       viewBox="0 0 24 24"
                       width="24"
                       xmlns="http://www.w3.org/2000/svg">
                            <path
                              d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                              fill="currentColor" />
                          </svg>
                        </span>
                <!--end::Svg Icon-->
              </div>
              <!--end::Advanced search toggle-->
            </div>
            <!--end::Toolbar-->
          </form>
          <!--end::Form-->

          <!--begin::Separator-->
          <div class="separator border-gray-200 mb-6"></div>
          <!--end::Separator-->
          <!--begin::Recently viewed-->
          <div class="d-none" data-kt-search-element="results">
            <!--begin::Items-->
            <div class="scroll-y mh-200px mh-lg-350px">
              <!--begin::Category title-->
              <h3 class="fs-5 text-muted m-0 pb-5" data-kt-search-element="category-title">
                Users
              </h3>
              <!--end::Category title-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                  <img alt="" src="@/assets/media/avatars/300-6.jpg" />
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Karina Clark</span>
                  <span class="fs-7 fw-semibold text-muted">Marketing Manager</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                  <img alt="" src="@/assets/media/avatars/300-2.jpg" />
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Olivia Bold</span>
                  <span class="fs-7 fw-semibold text-muted">Software Engineer</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                  <img alt="" src="@//assets/media/avatars/300-9.jpg" />
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Ana Clark</span>
                  <span class="fs-7 fw-semibold text-muted">UI/UX Designer</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                  <img alt="" src="@/assets/media/avatars/300-14.jpg" />
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Nick Pitola</span>
                  <span class="fs-7 fw-semibold text-muted">Art Director</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">

                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Edward Kulnic</span>
                  <span class="fs-7 fw-semibold text-muted">System Administrator</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->
              <!--begin::Category title-->
              <h3 class="fs-5 text-muted m-0 pt-5 pb-5" data-kt-search-element="category-title">
                Customers
              </h3>
              <!--end::Category title-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Company Rbranding</span>
                  <span class="fs-7 fw-semibold text-muted">UI Design</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                    <span class="symbol-label bg-light">
                    </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Company Re-branding</span>
                  <span class="fs-7 fw-semibold text-muted">Web Development</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                    <span class="symbol-label bg-light">
                    </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Business Analytics App</span>
                  <span class="fs-7 fw-semibold text-muted">Administration</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <a class="d-flex text-dark text-hover-primary align-items-center mb-5" href="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                    <span class="symbol-label bg-light">
                    </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">EcoLeaf App Launch</span>
                  <span class="fs-7 fw-semibold text-muted">Marketing</span>
                </div>
                <!--end::Title-->
              </a>
              <!--end::Item-->

              <!--begin::Item-->
              <router-link class="d-flex text-dark text-hover-primary align-items-center mb-5" to="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column justify-content-start fw-semibold">
                  <span class="fs-6 fw-semibold">Tower Group Website</span>
                  <span class="fs-7 fw-semibold text-muted">Google Adwords</span>
                </div>
                <!--end::Title-->
              </router-link>
              <!--end::Item-->

              <!--begin::Category title-->
              <h3 class="fs-5 text-muted m-0 pt-5 pb-5" data-kt-search-element="category-title">
                Projects
              </h3>
              <!--end::Category title-->

              <!--begin::Item-->
              <router-link class="d-flex text-dark text-hover-primary align-items-center mb-5" to="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                            <!--begin::Svg Icon -->
                            <span class="svg-icon svg-icon-2 svg-icon-primary">
                              <svg fill="none"
                                   height="24"
                                   viewBox="0 0 24 24"
                                   width="24"
                                   xmlns="http://www.w3.org/2000/svg">
                                <path d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z"
                                      fill="currentColor"
                                      opacity="0.3" />
                                <rect fill="currentColor" height="2" rx="1" width="6" x="7" y="17" />
                                <rect fill="currentColor" height="2" rx="1" width="10" x="7" y="12" />
                                <rect fill="currentColor" height="2" rx="1" width="6" x="7" y="7" />
                                <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="currentColor" />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <span class="fs-6 fw-semibold">Si-Fi Project by AU Themes</span>
                  <span class="fs-7 fw-semibold text-muted">#45670</span>
                </div>
                <!--end::Title-->
              </router-link>
              <!--end::Item-->

              <!--begin::Item-->
              <router-link class="d-flex text-dark text-hover-primary align-items-center mb-5" to="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
                            <span class="svg-icon svg-icon-2 svg-icon-primary">
                              <svg fill="none" height="24"
                                   viewBox="0 0 24 24" width="24"
                                   xmlns="http://www.w3.org/2000/svg">
                                <rect fill="currentColor" height="10" rx="1.5" width="3" x="8" y="9" />
                                <rect fill="currentColor" height="14" opacity="0.5" rx="1.5" width="3" x="13" y="5" />
                                <rect fill="currentColor" height="8" rx="1.5" width="3" x="18" y="11" />
                                <rect fill="currentColor" height="6" rx="1.5" width="3" x="3" y="13" />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <span class="fs-6 fw-semibold">Shopix Mobile App Planning</span>
                  <span class="fs-7 fw-semibold text-muted">#45690</span>
                </div>
                <!--end::Title-->
              </router-link>
              <!--end::Item-->

              <!--begin::Item-->
              <router-link class="d-flex text-dark text-hover-primary align-items-center mb-5" to="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com012.svg-->
                            <span class="svg-icon svg-icon-2 svg-icon-primary">
                              <svg fill="none" height="24"
                                   viewBox="0 0 24 24" width="24"
                                   xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 3H4C2.89543 3 2 3.89543 2 5V16C2 17.1046 2.89543 18 4 18H4.5C5.05228 18 5.5 18.4477 5.5 19V21.5052C5.5 22.1441 6.21212 22.5253 6.74376 22.1708L11.4885 19.0077C12.4741 18.3506 13.6321 18 14.8167 18H20C21.1046 18 22 17.1046 22 16V5C22 3.89543 21.1046 3 20 3Z"
                                      fill="currentColor"
                                      opacity="0.3" />
                                <rect fill="currentColor" height="2" rx="1" width="7" x="6" y="12" />
                                <rect fill="currentColor" height="2" rx="1" width="12" x="6" y="7" />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <span class="fs-6 fw-semibold">Finance Monitoring SAAS Discussion</span>
                  <span class="fs-7 fw-semibold text-muted">#21090</span>
                </div>
                <!--end::Title-->
              </router-link>
              <!--end::Item-->

              <!--begin::Item-->
              <router-link class="d-flex text-dark text-hover-primary align-items-center mb-5" to="#">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                          <span class="symbol-label bg-light">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com006.svg-->
                            <span class="svg-icon svg-icon-2 svg-icon-primary"><svg fill="none" height="18"
                                                                                    viewBox="0 0 18 18" width="18"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.5 9C16.5 13.125 13.125 16.5 9 16.5C4.875 16.5 1.5 13.125 1.5 9C1.5 4.875 4.875 1.5 9 1.5C13.125 1.5 16.5 4.875 16.5 9Z"
                                      fill="currentColor"
                                      opacity="0.3" />
                                <path
                                  d="M9 16.5C10.95 16.5 12.75 15.75 14.025 14.55C13.425 12.675 11.4 11.25 9 11.25C6.6 11.25 4.57499 12.675 3.97499 14.55C5.24999 15.75 7.05 16.5 9 16.5Z"
                                  fill="currentColor" />
                                <rect fill="currentColor" height="4" rx="2" width="4" x="7" y="6" />
                              </svg>
                            </span>
                            <!--end::Svg Icon-->
                          </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <span class="fs-6 fw-semibold">Dashboard Analitics Launch</span>
                  <span class="fs-7 fw-semibold text-muted">#34560</span>
                </div>
                <!--end::Title-->
              </router-link>
              <!--end::Item-->
            </div>
            <!--end::Items-->
          </div>
          <!--end::Recently viewed-->

          <!--begin::Recently viewed-->
          <div class="mb-5" data-kt-search-element="main">
            <!--begin::Heading-->
            <div class="d-flex flex-stack fw-semibold mb-4">
              <!--begin::Label-->
              <span class="text-muted fs-6 me-2">Recently Searched:</span>
              <!--end::Label-->
            </div>
            <!--end::Heading-->

            <!--begin::Items-->
            <div class="scroll-y mh-200px mh-lg-325px">
              <!--begin::Item-->
              <div class="d-flex align-items-center mb-5">
                <!--begin::Symbol-->
                <div class="symbol symbol-40px me-4">
                    <span class="symbol-label bg-light">
                      <!--begin::Svg Icon | path: icons/duotune/electronics/elc004.svg-->
                      <span class="svg-icon svg-icon-2 svg-icon-primary">
                        <svg fill="none" height="24"
                             viewBox="0 0 24 24" width="24"
                             xmlns="http://www.w3.org/2000/svg">
                          <path d="M2 16C2 16.6 2.4 17 3 17H21C21.6 17 22 16.6 22 16V15H2V16Z"
                                fill="currentColor" />
                          <path d="M21 3H3C2.4 3 2 3.4 2 4V15H22V4C22 3.4 21.6 3 21 3Z" fill="currentColor"
                                opacity="0.3" />
                          <path d="M15 17H9V20H15V17Z" fill="currentColor" opacity="0.3" />
                        </svg>
                      </span>
                      <!--end::Svg Icon-->
                    </span>
                </div>
                <!--end::Symbol-->

                <!--begin::Title-->
                <div class="d-flex flex-column" style="display: none;">
                  <a class="fs-6 text-gray-800 text-hover-primary fw-semibold" href="#"></a>
                  <span class="fs-7 text-muted fw-semibold">#45789</span>
                </div>
                <!--end::Title-->
              </div>
              <!--end::Item-->
            </div>
            <!--end::Items-->

          </div>
          <!--end::Recently viewed-->
          <!--begin::Empty-->
          <div class="text-center d-none" data-kt-search-element="empty">
            <!--begin::Icon-->
            <div class="pt-10 pb-10">
              <!--begin::Svg Icon | path: icons/duotune/files/fil024.svg-->
              <span class="svg-icon svg-icon-4x opacity-50"><svg fill="none" height="24" viewBox="0 0 24 24"
                                                                 width="24" xmlns="http://www.w3.org/2000/svg">
                          <path d="M14 2H6C4.89543 2 4 2.89543 4 4V20C4 21.1046 4.89543 22 6 22H18C19.1046 22 20 21.1046 20 20V8L14 2Z"
                                fill="currentColor"
                                opacity="0.3" />
                          <path d="M20 8L14 2V6C14 7.10457 14.8954 8 16 8H20Z" fill="currentColor" />
                          <rect fill="currentColor" height="1.73089" rx="0.865447" transform="rotate(45 13.6993 13.6656)" width="4.42828"
                                x="13.6993" y="13.6656" />
                          <path
                            d="M15 12C15 14.2 13.2 16 11 16C8.8 16 7 14.2 7 12C7 9.8 8.8 8 11 8C13.2 8 15 9.8 15 12ZM11 9.6C9.68 9.6 8.6 10.68 8.6 12C8.6 13.32 9.68 14.4 11 14.4C12.32 14.4 13.4 13.32 13.4 12C13.4 10.68 12.32 9.6 11 9.6Z"
                            fill="currentColor" />
                        </svg>
                      </span>
              <!--end::Svg Icon-->
            </div>
            <!--end::Icon-->

            <!--begin::Message-->
            <div class="pb-15 fw-semibold">
              <h3 class="text-gray-600 fs-5 mb-2">No result found</h3>
              <div class="text-muted fs-7">
                Please try again with a different query
              </div>
            </div>
            <!--end::Message-->
          </div>
          <!--end::Empty-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Preferences-->
        <form class="pt-1 d-none" data-kt-search-element="advanced-options-form">
          <!--begin::Heading-->
          <h3 class="fw-semibold text-dark mb-7">Advanced Search</h3>
          <!--end::Heading-->

          <!--begin::Input group-->
          <div class="mb-5">
            <input class="form-control form-control-sm form-control-solid" name="query"
                   placeholder="Contains the word" type="text" />
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="mb-5">
            <!--begin::Radio group-->
            <div class="nav-group nav-group-fluid">
              <!--begin::Option-->
              <label>
                <input checked="checked" class="btn-check" name="type" type="radio" value="has" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary">
                          All
                        </span>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label>
                <input class="btn-check" name="type" type="radio" value="users" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                          Users
                        </span>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label>
                <input class="btn-check" name="type" type="radio" value="orders" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                          Orders
                        </span>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label>
                <input class="btn-check" name="type" type="radio" value="projects" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                          Projects
                        </span>
              </label>
              <!--end::Option-->
            </div>
            <!--end::Radio group-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="mb-5">
            <input class="form-control form-control-sm form-control-solid" name="assignedto" placeholder="Assigned to"
                   type="text" value="" />
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="mb-5">
            <input class="form-control form-control-sm form-control-solid" name="collaborators" placeholder="Collaborators"
                   type="text" value="" />
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="mb-5">
            <!--begin::Radio group-->
            <div class="nav-group nav-group-fluid">
              <!--begin::Option-->
              <label>
                <input checked="checked" class="btn-check" name="attachment" type="radio" value="has" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary">
                          Has attachment
                        </span>
              </label>
              <!--end::Option-->

              <!--begin::Option-->
              <label>
                <input class="btn-check" name="attachment" type="radio" value="any" />
                <span class="btn btn-sm btn-color-muted btn-active btn-active-primary px-4">
                          Any
                        </span>
              </label>
              <!--end::Option-->
            </div>
            <!--end::Radio group-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="mb-5">
            <select aria-label="Select a Timezone" class="form-select form-select-sm form-select-solid" data-control="select2"
                    data-placeholder="date_period" name="timezone">
              <option value="next">Within the next</option>
              <option value="last">Within the last</option>
              <option value="between">Between</option>
              <option value="on">On</option>
            </select>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row mb-8">
            <!--begin::Col-->
            <div class="col-6">
              <input class="form-control form-control-sm form-control-solid" name="date_number" placeholder="Lenght"
                     type="number" value="" />
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-6">
              <select aria-label="Select a Timezone" class="form-select form-select-sm form-select-solid" data-control="select2"
                      data-placeholder="Period" name="date_typer">
                <option value="days">Days</option>
                <option value="weeks">Weeks</option>
                <option value="months">Months</option>
                <option value="years">Years</option>
              </select>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="d-flex justify-content-end">
            <button class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2" data-kt-search-element="advanced-options-form-cancel"
                    type="reset">
              Cancel
            </button>

            <a class="btn btn-sm fw-bold btn-primary" data-kt-search-element="advanced-options-form-search"
               href="search/horizontal.html">Search</a>
          </div>
          <!--end::Actions-->
        </form>
        <!--end::Preferences-->
        <!--begin::Preferences-->
        <form class="pt-1 d-none" data-kt-search-element="preferences">
          <!--begin::Heading-->
          <h3 class="fw-semibold text-dark mb-7">Search Preferences</h3>
          <!--end::Heading-->

          <!--begin::Input group-->
          <div class="pb-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                      <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                        Projects
                      </span>

              <input checked="checked" class="form-check-input" type="checkbox" value="1" />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="py-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                      <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                        Targets
                      </span>
              <input checked="checked" class="form-check-input" type="checkbox" value="1" />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="py-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                      <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                        Affiliate Programs
                      </span>
              <input class="form-check-input" type="checkbox" value="1" />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="py-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                      <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                        Referrals
                      </span>
              <input checked="checked" class="form-check-input" type="checkbox" value="1" />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="py-4 border-bottom">
            <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                      <span class="form-check-label text-gray-700 fs-6 fw-semibold ms-0 me-2">
                        Users
                      </span>
              <input class="form-check-input" type="checkbox" value="1" />
            </label>
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="d-flex justify-content-end pt-7">
            <button class="btn btn-sm btn-light fw-bold btn-active-light-primary me-2" data-kt-search-element="preferences-dismiss"
                    type="reset">
              Cancel
            </button>
            <button class="btn btn-sm fw-bold btn-primary" type="submit">
              Save Changes
            </button>
          </div>
          <!--end::Actions-->
        </form>
        <!--end::Preferences-->
      </div>
      <!--end::Menu-->
    </div>
    <!--end::Search-->
  </div>
  <!--end::Search-->
</template>

<script>
export default {
  name: "HeaderNavSearch"
};
</script>

<style scoped>

</style>