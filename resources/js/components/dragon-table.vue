<template>
    <table>
        <thead>
            <tr>
                <th v-for="n in count">
                    <slot :name="'body-'+n"></slot>
                </th>
            </tr>
        </thead>
        <tbody>
            <!-- <tr>
                <td>
                    <h5>
                        <slot name="body-1"></slot>
                    </h5>
                </td>
                
            </tr> -->
            <slot name="dragon-td"></slot>
        </tbody>
    </table>
</template>
<script>
export default {
    data: function() {
        return {
            dogName: 'Roger',
            count:3
        }
    }

}
</script>