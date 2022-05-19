<template>
  <div
    class="modal fade"
    @click.self="closeModal"
    :class="[
      { 'show d-block': show },
      { 'd-none': !show },
      { 'modal-mini': type === 'mini' },
    ]"
    v-show="show"
    tabindex="-1"
    role="dialog"
    :aria-hidden="!show"
  >
    <div
      class="modal-dialog modal-dialog-centered modal-xl"
      :class="[{ 'modal-notice': type === 'notice' }, modalClasses]"
    >
      <div
        class="modal-content px-lg-5 pt-lg-3 overflow-auto max-h-modal"
        :class="[
          gradient ? `bg-gradient-${gradient}` : '',
          modalContentClasses,
          mainClasses
        ]"
      >
        <div class="modal-header" :class="[headerClasses]" v-if="$slots.header">
            <slot name="header"></slot>
            <slot name="close-button">
              <button
                type="button"
                class="close"
                v-if="showClose"
                @click="closeModal"
                data-dismiss="modal"
                aria-label="Close"
              >
                <span :aria-hidden="!show">×</span>
              </button>
            </slot>
        </div>
        <div v-else class="modal-header d-block border-bottom border-light" :class="[headerClasses]">
          <div class="d-flex justify-content-between align-items-center">
            <div v-if="$slots.header_2">
              <slot name="header_2"></slot>
            </div>
            <div v-else class="d-flex align-items-center text-uppercase">
              <h3>{{model}} {{this.$router.name}}</h3>
              <span class="text-muted h6 ml-md-1 text-capitalize"> - {{action}}</span>
            </div>
            <div>
              <button
                  type="button"
                  class="close"
                  v-if="showClose"
                  @click="closeModal"
                  data-dismiss="modal"
                  aria-label="Close"
                >
                  <span :aria-hidden="!show">×</span>
                </button>
            </div>
          </div>
        </div>

        <div class="modal-body" :class="bodyClasses">
          <slot></slot>
        </div>

        <div class="modal-footer" :class="footerClasses" v-if="$slots.footer">
          <slot name="footer"></slot>
        </div>
      </div>
    </div>
    <loader></loader>
  </div>
</template>
<script>
export default {
  name: "modal",
  props: {
    show: Boolean,
    showClose: {
      type: Boolean,
      default: true,
    },
    type: {
      type: String,
      default: "",
      validator(value) {
        let acceptedValues = ["", "notice", "mini"];
        return acceptedValues.indexOf(value) !== -1;
      },
      description: 'Modal type (notice|mini|"") ',
    },
    model: {type: String},
    action: {type: String, default: 'registrar'},
    modalClasses: {
      type: [Object, String],
      description: "Modal dialog css classes",
    },
    mainClasses: {
      type: [Object, String],
      description: "Modal dialog css classes",
    },
    modalContentClasses: {
      type: [Object, String],
      description: "Modal dialog content css classes",
    },
    gradient: {
      type: String,
      description: "Modal gradient type (danger, default etc)",
    },
    headerClasses: {
      type: [Object, String],
      description: "Modal Header css classes",
    },
    bodyClasses: {
      type: [Object, String],
      description: "Modal Body css classes",
    },
    footerClasses: {
      type: [Object, String],
      description: "Modal Footer css classes",
    },
    animationDuration: {
      type: Number,
      default: 500,
      description: "Modal transition duration",
    },
  },
  methods: {
    closeModal() {
      this.$emit("update:show", false);
      this.$emit("close");
    },
  },
  watch: {
    show(val) {
      let documentClasses = document.body.classList;
      if (val) {
        documentClasses.add("modal-open");
      } else {
        documentClasses.remove("modal-open");
      }
    },
  },
};
</script>
<style>
.modal.show {
  background-color: rgba(0, 0, 0, 0.3);
}
</style>
