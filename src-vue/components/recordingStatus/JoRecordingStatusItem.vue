<template>
  <tr>
    <td class="status_icon column">
      <span class="c-dot" :class="dotClass">{{item.status}}</span>
    </td>
    <td class="column">
      <small class="u-muted">
        <span class="c-badge">{{item.location}}</span>
        {{path}}
      </small>
      <br/>{{file}}
    </td>
    <td class="column u-text-right">{{size}}</td>
    <td class="column u-text--">
      {{item.resolution}}<br/>{{item.bitrate}} kb/s
    </td>
  </tr>
</template>

<script>
import last from 'lodash/last'
import filesize from 'filesize'

export default {
  props: ['item'],
  computed: {
    dotClass () {
      const status = Number(this.item.status)
      if (status < 5) return 'c-dot--yellow is-loading'
      else if (status >= 60) return 'c-dot--red'
      else return 'c-dot--green'
    },
    file () {
      return last(this.item.relative_url.split('/'))
    },
    path () {
      return (
        this.item.relative_url
          .split('/')
          .slice(0, -1)
          .join('/') + '/'
      )
    },
    size () {
      return filesize(this.item.size)
    },
  },
}
</script>
