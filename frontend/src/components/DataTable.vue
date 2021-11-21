<template>
  <VueGoodTable
      v-bind="$attrs"
      styleClass="data-table"
      class="data-table"
  >
    <template slot="table-column" slot-scope="props">
      <div>
        <div class="label">
          {{ props.column.label }}
          <div class="arrows" v-if="props.column.sortable">
            <i class="arrow up"></i>
            <i class="arrow down"></i>
          </div>
        </div>
        <div class="additional">{{ props.column.label_additional }}</div>
      </div>
    </template>
  </VueGoodTable>
</template>

<script>
import {VueGoodTable} from 'vue-good-table';

export default {
  name: 'DataTable',
  components: {VueGoodTable},
};
</script>

<style lang="scss">
.data-table {
  width: 100%;

  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }

  .text-bold span {
    font-weight: bold;
  }

  .sortable {
    button {
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background: transparent;
      border: none;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;

      &:focus {
        outline: none;
      }
    }

    span {
      &:after {
        content: '';
        position: absolute;
        height: 0px;
        width: 0px;
        right: 6px;
        top: 50%;
        margin-top: -7px;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-bottom: 4px solid black;
      }

      &:before {
        content: '';
        position: absolute;
        height: 0px;
        width: 0px;
        right: 6px;
        top: 50%;
        margin-bottom: -7px;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid black;
      }
    }
  }

  .sorting-asc .arrow.down {
    display: none;
  }

  .sorting-desc .arrow.up {
    display: none;
  }

  .vgt-inner-wrap {
    border-radius: 0;
    box-shadow: none;
  }

  tr:not(:last-child) {
    border-bottom: 1px solid rgba(61, 117, 228, 0.08);
  }

  th {
    position: relative;
    font-style: normal;
    font-weight: 500;
    font-size: 13px;
    line-height: 16px;
    opacity: 0.48;
  }

  th, td {
    padding: 24px 0;
    text-align: left;
  }
}

.label {
  position: relative;
}

.additional {
  font-size: 10px;
  line-height: 12px;
  margin-top: 4px;
}

.arrows {
  display: inline-block;
  margin-left: 5px;
}

.arrow {
  position: absolute;
  border: solid black;
  border-width: 0 2px 2px 0;
  display: block;
  padding: 2px;
}

.up {
  transform: rotate(-135deg);
  top: 35%;
  -webkit-transform: rotate(-135deg);
}

.down {
  top: 53%;
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
}
</style>