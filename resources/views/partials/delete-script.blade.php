<script>
function deleteItem(url, label) {
    Swal.fire({
        title: `Supprimer ${label} ?`,
        text: 'Cette action est irréversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#DC2626',
        cancelButtonColor: '#6B7280',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Supprimer',
        background: '#FDFAF5',
        color: '#1A2B3C',
    }).then(result => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-HTTP-Method-Override': 'DELETE',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ _method: 'DELETE' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Supprimé !',
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#FDFAF5',
                    }).then(() => window.location.reload());
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'La suppression a échoué.',
                });
            });
        }
    });
}
</script>