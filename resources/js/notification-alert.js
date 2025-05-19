document.addEventListener('livewire:init', () => {
    // On Success
    Livewire.on('success', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: element.title,
                text: element.message,
                icon: element.status,
                allowOutsideClick: false,
            });
        });
    });

    // On Error
    Livewire.on('error', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: element.title,
                text: element.message,
                icon: 'error',
                allowOutsideClick: false,
            });
        });
    });

    // On Info
    Livewire.on('info', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: element.title,
                text: element.message,
                icon: 'info',
                allowOutsideClick: false,
            });
        });
    });

    // On Warning
    Livewire.on('warning', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: element.title,
                text: element.message,
                icon: 'warning',
                allowOutsideClick: false,
            });
        });
    });

    // On Question
    Livewire.on('question', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: element.title,
                text: element.message,
                icon: 'question',
                allowOutsideClick: false,
            });
        });
    });

    // On Create Task
    Livewire.on('add_task', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: 'No Task!',
                text: element.message,
                icon: 'info',
                showCancelButton: true,
                cancelButtonColor: "#d33",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes, Proceed",
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    Flux.modal('add-new-task').show()
                }
            });
        });
    });

    // On View Task
    Livewire.on('view-task', (event) => {
        event.forEach(function(element){
            Swal.fire({
                title: 'Event to view Task',
                text: 'Checking if this would work',
                icon: 'question',
                allowOutsideClick: false,
            });
        });
    });
});