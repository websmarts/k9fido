

const app =new Vue({
    el: '#table',
    data: {
        columns: [
            { label: 'ID', field: 'id', align: 'center', filterable: false },
            { label: 'Username', field: 'user.username' },
            { label: 'First Name', field: 'user.first_name' },
            { label: 'Last Name', field: 'user.last_name' },
            { label: 'Email', field: 'user.email', align: 'right', sortable: false }
        ],
        rows: window.rows
    }
});