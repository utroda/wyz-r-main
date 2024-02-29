<script setup>
  const props = defineProps({
    protein: {
      type: Object,
      required: true
    }
  });
</script>

<template>
  <div class="promo">
    <div class="promo__flag">Featured</div>
    <div class="promo__name">
      {{ protein.name }}
    </div>
    <div class="promo__description">
      {{ protein.description }}
    </div>
    <a href="#" class="cta">Add {{ protein.name }} To Cart.</a>
  </div>
</template>

<style scoped>
  .promo {
    position: relative;
    border: 1px solid #476d7b;
    padding: 1rem;
    margin: 1rem 0;
    border-radius: var(--border-radius);
    background: #e4f1f6;
    box-shadow: 0 1px 3px rgba(12, 12, 12, 0.09);
    text-align: center;
  }

  .promo__name {
    font-weight: 600;
  }

  .cta {
    background: var(--surface-accent);
    border-radius: var(--border-radius);

    display: inline-block;
    margin-top: 1rem;
    color: #fff;
    padding: 0.5rem 1rem;
  }

  .promo__flag {
    position: absolute;
    top: -0.5rem;
    left: -0.5rem;
    background: #f08638;
    color: #411a03;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 5px;
  }
</style>
