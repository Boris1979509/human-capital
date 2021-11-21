<template>
  <div class="search">
    <input type="search" v-model="query" @keyup.enter="search" placeholder="Поиск">
    <Button class="clear-btn" v-if="query.length>0" @click.native="reset">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g opacity="0.72" clip-path="url(#clip0)">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L8 6.58579L14.2929 0.292893C14.6834 -0.0976311 15.3166 -0.0976311 15.7071 0.292893C16.0976 0.683417 16.0976 1.31658 15.7071 1.70711L9.41421 8L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L8 9.41421L1.70711 15.7071C1.31658 16.0976 0.683417 16.0976 0.292893 15.7071C-0.0976311 15.3166 -0.0976311 14.6834 0.292893 14.2929L6.58579 8L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z"
                fill="#3D75E4"/>
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="16" height="16" fill="white"/>
          </clipPath>
        </defs>
      </svg>
    </Button>
    <Button
        class="btn btn--blue search-btn"
        @click.native="search"
    >
      Найти
    </Button>
  </div>
</template>

<script>
export default {
  name: 'SearchInput',
  props: {
    value: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      query: '',
    };
  },
  watch: {
    value(val) {
      this.query = val;
    },
  },
  methods: {
    search() {
      this.$emit('search', this.query);
    },
    reset() {
      this.query = '';
    },
  },
};
</script>

<style scoped lang="scss">
.search {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;

  input {
    width: 100%;
    height: 64px;

    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPGcgb3BhY2l0eT0iMC40OCI+CjxwYXRoIGQ9Ik0yIDYuNUMyIDQuMDE0NzIgNC4wMTQ3MiAyIDYuNSAyQzguOTg1MjggMiAxMSA0LjAxNDcyIDExIDYuNUMxMSA2LjkyNzA5IDEwLjk0MDggNy4zMzg2OSAxMC44MzA3IDcuNzI3ODRDMTAuNjgwNCA4LjI1OTI4IDEwLjk4OTQgOC44MTE5NCAxMS41MjA4IDguOTYyMjVDMTIuMDUyMiA5LjExMjU2IDEyLjYwNDkgOC44MDM2IDEyLjc1NTIgOC4yNzIxNkMxMi45MTQ5IDcuNzA3NjUgMTMgNy4xMTI5MyAxMyA2LjVDMTMgMi45MTAxNSAxMC4wODk5IDAgNi41IDBDMi45MTAxNSAwIDAgMi45MTAxNSAwIDYuNUMwIDEwLjA4OTkgMi45MTAxNSAxMyA2LjUgMTNDNy45MzM2NiAxMyA5LjI1OTk1IDEyLjUzNSAxMC4zMzQ2IDExLjc0ODhMMTQuMjkyOSAxNS43MDcxQzE0LjY4MzQgMTYuMDk3NiAxNS4zMTY2IDE2LjA5NzYgMTUuNzA3MSAxNS43MDcxQzE2LjA5NzYgMTUuMzE2NiAxNi4wOTc2IDE0LjY4MzQgMTUuNzA3MSAxNC4yOTI5TDExLjIwNzEgOS43OTI4OUMxMS4xNjM4IDkuNzQ5NjMgMTEuMTE3NiA5LjcxMTE3IDExLjA2OTEgOS42Nzc0OUMxMC42Nzg0IDkuMzAzNTQgMTAuMDU4NSA5LjMwNzYzIDkuNjcyODMgOS42OTExQzguODU4MTUgMTAuNTAxMiA3LjczODMgMTEgNi41IDExQzQuMDE0NzIgMTEgMiA4Ljk4NTI4IDIgNi41WiIgZmlsbD0iIzNENzVFNCIvPgo8L2c+Cjwvc3ZnPg==);
    background-size: 16px;
    background-position: left 16px top 50%;
    background-repeat: no-repeat;
    padding: 22px 177px 22px 60px;
    background-color: var(--main-color-trans-light);
    border-radius: 32px;

    &::-webkit-search-decoration,
    &::-webkit-search-cancel-button,
    &::-webkit-search-results-button,
    &::-webkit-search-results-decoration {
      display: none;
    }

    &:focus {
      outline: none;
    }
  }

  .clear-btn {
    position: absolute;
    right: 150px;
    top: 12px;

    width: 32px;
    height: 40px;

    opacity: 0.72;

    padding: 0;

    use {
      fill: var(--main-color);
    }

    &:hover {
      opacity: 1;
    }
  }

  &-btn {
    padding: 22px 48px;
    height: 64px;
    width: 145px;
    right: 0;
    border-radius: 32px;
    position: absolute;
  }
}
</style>