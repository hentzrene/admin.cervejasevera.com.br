<template lang="pug">
svg.mrx-loading(viewBox="0 0 105 76")
  path(
    d="M104.4,0 L46,35.6 L105,50.6 L62.4,35.6 z M0.6,76 L59,40.3 L0,25.3 L42.6,40.3 z"
  )
</template>

<style >
.mrx-loading {
  fill: var(--v-secondary-base);
  height: 60px;
}

.mrx-loading path {
  animation: d 1800ms infinite alternate ease-in-out,
    f 3600ms infinite steps(1, end);
}
@keyframes f {
  0% {
    fill: #e93751;
  }
  50% {
    fill: #00a7bc;
  }
}

@keyframes d {
  0% {
    d: path(
      "M104.4,0 L104.4,0 L104.4,0 L104.4,0 z M0.6,76 L0.6,76 L0.6,76 L0.6,76 z"
    );
  }
  25% {
    d: path(
      "M104.4,0 L46,35.6 L46,35.6 L62.4,35.6 z M0.6,76 L59,40.3 L59,40.3 L42.6,40.3 z"
    );
  }
  50% {
    d: path(
      "M104.4,0 L46,35.6 L105,50.6 L62.4,35.6 z M0.6,76 L59,40.3 L0,25.3 L42.6,40.3 z"
    );
  }
  75% {
    d: path(
      "M46,35.6 L46,35.6 L105,50.6 L62.4,35.6 z M59,40.3 L59,40.3 L0,25.3 L42.6,40.3 z"
    );
  }
  100% {
    d: path(
      "M105,50.6 L105,50.6 L105,50.6 L105,50.6 z M0,25.3 L0,25.3 L0,25.3 L0,25.3 z"
    );
  }
}
</style>
