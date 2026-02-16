<!-- Custom Styles -->
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 80px 0 100px;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,138.7C960,139,1056,117,1152,101.3C1248,85,1344,75,1392,69.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
        animation: wave 10s ease-in-out infinite;
    }

    @keyframes wave {
        0%, 100% { transform: translateX(0); }
        50% { transform: translateX(20px); }
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        animation: fadeInDown 0.8s ease-out;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        font-weight: 300;
        opacity: 0.95;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-section {
        margin-top: -60px;
        position: relative;
        z-index: 10;
    }

    .form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        animation: slideUp 0.8s ease-out;
    }

    .form-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.15);
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card-header-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 20px 20px 0 0 !important;
        border: none;
    }

    .card-header-custom h3 {
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0;
    }

    .card-header-custom i {
        font-size: 2.5rem;
        margin-bottom: 10px;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .form-label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-2px);
    }

    .form-control:hover, .form-select:hover {
        border-color: #667eea;
    }

    .btn-custom-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 14px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-custom-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }

    .btn-custom-secondary {
        background: #e2e8f0;
        color: #2d3748;
        border: none;
        border-radius: 10px;
        padding: 14px 30px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-custom-secondary:hover {
        background: #cbd5e0;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .info-card {
        border: none;
        border-radius: 15px;
        background: linear-gradient(135deg, #f6f9fc 0%, #e9ecef 100%);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        animation: fadeIn 1s ease-out;
    }

    .info-card .card-title {
        color: #667eea;
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 20px;
    }

    .info-item {
        background: white;
        border: none;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .info-item:hover {
        transform: translateX(10px);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.15);
    }

    .info-icon {
        font-size: 1.2rem;
        color: #667eea;
        margin-right: 10px;
    }

    .alert-custom {
        border-radius: 12px;
        border: none;
        padding: 16px 20px;
        font-weight: 500;
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-success {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        color: white;
    }

    .icon-wrapper {
        display: inline-block;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
    }

    .type-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 600;
        margin: 5px;
        transition: all 0.3s ease;
    }

    .type-nature {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .type-materiaux {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }

    .type-argent {
        background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
        color: white;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
    }
</style>

<!-- Hero Section Start -->
<section class="hero-section">
    <div class="container text-center text-white position-relative">
        <i class="fas fa-hands-helping" style="font-size: 4rem; margin-bottom: 20px; opacity: 0.9;"></i>
        <h1 class="hero-title">Ajouter un Besoin</h1>
        <p class="hero-subtitle">Enregistrez les besoins des villes pour mieux organiser l'aide humanitaire</p>
    </div>
</section>
<!-- Hero Section End -->

<!-- Form Section Start -->
<section class="form-section pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8">
                
                <!-- Main Form Card -->
                <div class="card form-card mb-4">
                    <div class="card-header card-header-custom text-white text-center">
                        <i class="fas fa-edit"></i>
                        <h3>Formulaire d'enregistrement</h3>
                        <p class="mb-0" style="opacity: 0.9; font-size: 0.95rem;">Remplissez tous les champs obligatoires</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <!-- Alert Messages -->
                        <?php if (isset($success) && $success): ?>
                        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <strong>Succès !</strong><br>
                                    <?= htmlspecialchars($success) ?>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if (isset($error) && $error): ?>
                        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <strong>Erreur !</strong><br>
                                    <?= htmlspecialchars($error) ?>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <!-- Form -->
                        <form action="/besoin/insert" method="POST" id="besoinForm" class="needs-validation" novalidate>
                            
                            <!-- Libellé du besoin -->
                            <div class="mb-4">
                                <label for="libelle_besoin" class="form-label">
                                    <i class="fas fa-tag me-2 text-primary"></i>Libellé du besoin 
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white" style="border-radius: 10px 0 0 10px; border: 2px solid #e2e8f0; border-right: none;">
                                        <i class="fas fa-pencil-alt text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="libelle_besoin" 
                                           name="libelle_besoin" 
                                           placeholder="Ex: Riz, Médicaments, Tôles, Eau potable..." 
                                           style="border-left: none;"
                                           required>
                                    <div class="invalid-feedback">Veuillez entrer le libellé du besoin.</div>
                                </div>
                            </div>

                            <!-- Type de besoin -->
                            <div class="mb-4">
                                <label for="type_besoin" class="form-label">
                                    <i class="fas fa-list-ul me-2 text-primary"></i>Type de besoin 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="type_besoin" name="type_besoin" required>
                                    <option value="">-- Sélectionner un type --</option>
                                    <option value="nature" data-icon="🌾">🌾 Nature (Produits alimentaires, consommables)</option>
                                    <option value="materiaux" data-icon="🏗️">🏗️ Matériaux (Construction, équipements)</option>
                                    <option value="argent" data-icon="💰">💰 Argent (Aide financière, fonds)</option>
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner un type de besoin.</div>
                            </div>

                            <!-- Quantité et Prix -->
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="quantite" class="form-label">
                                        <i class="fas fa-boxes me-2 text-primary"></i>Quantité 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" style="border-radius: 10px 0 0 10px; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-sort-numeric-up text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="quantite" 
                                               name="quantite" 
                                               min="1" 
                                               placeholder="Ex: 100"
                                               style="border-left: none;"
                                               required>
                                        <div class="invalid-feedback">Veuillez entrer la quantité.</div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label for="prix_unitaire" class="form-label">
                                        <i class="fas fa-money-bill-wave me-2 text-primary"></i>Prix unitaire (Ar) 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white" style="border-radius: 10px 0 0 10px; border: 2px solid #e2e8f0; border-right: none;">
                                            <i class="fas fa-dollar-sign text-muted"></i>
                                        </span>
                                        <input type="number" 
                                               class="form-control" 
                                               id="prix_unitaire" 
                                               name="prix_unitaire" 
                                               step="0.01" 
                                               min="0.01" 
                                               placeholder="Ex: 1500.00"
                                               style="border-left: none;"
                                               required>
                                        <div class="invalid-feedback">Veuillez entrer un prix unitaire valide.</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ville concernée -->
                            <div class="mb-4">
                                <label for="id_ville" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>Ville concernée 
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="id_ville" name="id_ville" required>
                                    <option value="">-- Sélectionner une ville --</option>
                                    <?php if (isset($villes) && is_array($villes)): ?>
                                        <?php foreach ($villes as $ville): ?>
                                            <option value="<?= htmlspecialchars($ville['id_ville']) ?>">
                                                📍 <?= htmlspecialchars($ville['nom_ville']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner une ville.</div>
                            </div>

                            <!-- Preview Section -->
                            <div id="preview-section" class="mb-4 p-3 rounded" style="background: #f7fafc; border-left: 4px solid #667eea; display: none;">
                                <h6 class="text-primary mb-2"><i class="fas fa-eye me-2"></i>Aperçu</h6>
                                <div id="preview-content" class="small text-muted"></div>
                            </div>

                            <!-- Buttons -->
                            <div class="d-grid gap-3 mt-5">
                                <button type="submit" class="btn btn-custom-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Enregistrer le besoin
                                </button>
                                <a href="/" class="btn btn-custom-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Retour à l'accueil
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card info-card">
                    <div class="card-body p-4">
                        <h5 class="card-title">
                            <i class="fas fa-info-circle me-2"></i>Informations importantes
                        </h5>
                        <div class="info-item">
                            <i class="fas fa-star info-icon"></i>
                            <strong>Champs obligatoires :</strong> Tous les champs marqués d'un <span class="text-danger">*</span> sont obligatoires
                        </div>
                        <div class="info-item">
                            <i class="fas fa-leaf info-icon"></i>
                            <strong>Type Nature :</strong> Produits alimentaires, eau, vêtements et consommables
                        </div>
                        <div class="info-item">
                            <i class="fas fa-tools info-icon"></i>
                            <strong>Type Matériaux :</strong> Matériaux de construction, équipements et outils
                        </div>
                        <div class="info-item">
                            <i class="fas fa-coins info-icon"></i>
                            <strong>Type Argent :</strong> Aide financière et fonds monétaires
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calculator info-icon"></i>
                            <strong>Calcul automatique :</strong> Le montant total est calculé automatiquement (Quantité × Prix unitaire)
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Form Section End -->

<!-- JavaScript -->
<script>
// Bootstrap Form Valitation
(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();

// Live Preview
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('besoinForm');
    const previewSection = document.getElementById('preview-section');
    const previewContent = document.getElementById('preview-content');
    
    const inputs = form.querySelectorAll('input, select');
    
    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
        input.addEventListener('change', updatePreview);
    });
    
    function updatePreview() {
        const libelle = document.getElementById('libelle_besoin').value;
        const type = document.getElementById('type_besoin').value;
        const quantite = document.getElementById('quantite').value;
        const prix = document.getElementById('prix_unitaire').value;
        const ville = document.getElementById('id_ville');
        const villeText = ville.options[ville.selectedIndex]?.text || '';
        
        if (libelle || type || quantite || prix || villeText) {
            previewSection.style.display = 'block';
            
            const montantTotal = quantite && prix ? (parseFloat(quantite) * parseFloat(prix)).toLocaleString('fr-FR', {minimumFractionDigits: 2}) : '0.00';
            
            let typeText = '';
            let typeIcon = '';
            if (type === 'nature') {
                typeText = 'Nature';
                typeIcon = '🌾';
            } else if (type === 'materiaux') {
                typeText = 'Matériaux';
                typeIcon = '🏗️';
            } else if (type === 'argent') {
                typeText = 'Argent';
                typeIcon = '💰';
            }
            
            previewContent.innerHTML = `
                ${libelle ? `<div><strong>Besoin:</strong> ${libelle}</div>` : ''}
                ${type ? `<div><strong>Type:</strong> ${typeIcon} ${typeText}</div>` : ''}
                ${quantite ? `<div><strong>Quantité:</strong> ${quantite}</div>` : ''}
                ${prix ? `<div><strong>Prix unitaire:</strong> ${parseFloat(prix).toLocaleString('fr-FR', {minimumFractionDigits: 2})} Ar</div>` : ''}
                ${montantTotal !== '0.00' ? `<div><strong>Montant total:</strong> <span class="text-primary fw-bold">${montantTotal} Ar</span></div>` : ''}
                ${villeText ? `<div><strong>Ville:</strong> ${villeText}</div>` : ''}
            `;
        } else {
            previewSection.style.display = 'none';
        }
    }
});

// Add animations on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.info-item').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'all 0.5s ease';
    observer.observe(item);
});
</script>
