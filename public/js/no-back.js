// Evita volver atrás después de cerrar sesión
if (window.performance && window.performance.navigation.type === 2) {
    window.location.href = '/';
}
