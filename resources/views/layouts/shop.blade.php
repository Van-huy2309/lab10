<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trang Sức Của Huy') – Trang Sức Cao Cấp</title>
    <meta name="description" content="Trang Sức Của Huy – Cửa hàng trang sức vàng, bạc, kim cương cao cấp. Nhẫn, vòng tay, dây chuyền, bông tai chính hãng.">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --gold:       #c9a84c;
            --gold-light: #e8d5a3;
            --gold-dark:  #9e7b2f;
            --rose:       #c2798a;
            --rose-light: #f5e6ea;
            --dark:       #1a1208;
            --dark2:      #2d2416;
            --cream:      #fdf9f2;
            --muted:      #7a6e5f;
            --border:     #e8dcc8;
            --white:      #ffffff;
            --shadow-sm:  0 2px 8px rgba(26,18,8,.08);
            --shadow-md:  0 8px 24px rgba(26,18,8,.12);
            --shadow-lg:  0 20px 50px rgba(26,18,8,.18);
            --radius:     14px;
            --radius-sm:  8px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--cream);
            color: var(--dark);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* ─── NAVBAR ─── */
        .shop-nav {
            background: rgba(253,249,242,.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 100;
            padding: 0 24px;
        }
        .nav-inner {
            max-width: 1200px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            height: 70px;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .nav-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: .9rem;
        }
        .nav-brand-text { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: var(--dark); }
        .nav-brand-text span { color: var(--gold); }
        .nav-links { display: flex; align-items: center; gap: 4px; }
        .nav-links a {
            font-size: .9rem; font-weight: 600; color: #3f382f;
            text-decoration: none; padding: 8px 14px; border-radius: var(--radius-sm);
            transition: color .2s, background .2s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--gold-dark); background: rgba(201,168,76,.1); }
        .nav-actions { display: flex; align-items: center; gap: 10px; }
        .nav-user-info { font-size: .85rem; font-weight: 600; color: var(--dark); }
        .btn-nav-outline {
            border: 1.5px solid #9e7b2f; color: #5c4314;
            background: #fff; border-radius: 999px;
            padding: 8px 18px; font-size: .85rem; font-weight: 700;
            text-decoration: none; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
            cursor: pointer;
        }
        .btn-nav-outline:hover { background: var(--gold); color: #fff; text-decoration: none; }
        .btn-nav-solid {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff; border: none; border-radius: 999px;
            padding: 8px 20px; font-size: .85rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
            display: inline-flex; align-items: center; gap: 6px;
            box-shadow: 0 4px 14px rgba(201,168,76,.35);
        }
        .btn-nav-solid:hover { opacity: .9; color: #fff; text-decoration: none; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(201,168,76,.45); }
        .logout-form { margin: 0; }

        /* ─── FLASH ─── */
        .flash-wrap { max-width: 1200px; margin: 0 auto; padding: 16px 24px 0; }
        .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 12px; }
        .alert-danger  { background: #fff5f5; border: 1px solid #fca5a5; color: #991b1b; border-radius: var(--radius-sm); padding: 12px 16px; margin-bottom: 12px; }

        /* ─── MAIN ─── */
        .container-shop { max-width: 1200px; margin: 0 auto; padding: 32px 24px; }

        /* ─── HERO ─── */
        .shop-hero {
            background: linear-gradient(135deg, var(--dark) 0%, var(--dark2) 50%, #3d2e10 100%);
            border-radius: 20px; padding: 60px 48px; margin-bottom: 48px;
            color: #fff; position: relative; overflow: hidden;
        }
        .shop-hero::before {
            content: '';
            position: absolute; top: -50%; right: -10%; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(201,168,76,.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .shop-hero::after {
            content: '';
            position: absolute; bottom: -30%; left: 20%; width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(194,121,138,.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .shop-hero-content { position: relative; z-index: 1; max-width: 580px; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(201,168,76,.2); border: 1px solid rgba(201,168,76,.4);
            color: var(--gold-light); border-radius: 999px;
            padding: 6px 14px; font-size: .78rem; font-weight: 600;
            margin-bottom: 20px; letter-spacing: .5px; text-transform: uppercase;
        }
        .shop-hero h1 { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 800; line-height: 1.2; margin-bottom: 16px; }
        .shop-hero h1 span { color: var(--gold-light); }
        .shop-hero p { color: rgba(255,255,255,.75); font-size: 1.05rem; margin-bottom: 28px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-hero {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff; border: none; border-radius: 999px;
            padding: 14px 28px; font-size: .95rem; font-weight: 700;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            box-shadow: 0 6px 20px rgba(201,168,76,.4);
            transition: all .2s;
        }
        .btn-hero:hover { opacity: .9; color: #fff; text-decoration: none; transform: translateY(-2px); }
        .btn-hero-outline {
            background: transparent; color: #fff;
            border: 1.5px solid rgba(255,255,255,.4); border-radius: 999px;
            padding: 14px 28px; font-size: .95rem; font-weight: 600;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            transition: all .2s;
        }
        .btn-hero-outline:hover { background: rgba(255,255,255,.1); color: #fff; text-decoration: none; }

        /* ─── FILTER BAR ─── */
        .filter-bar {
            background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
            padding: 20px 24px; margin-bottom: 32px;
            display: flex; align-items: center; gap: 16px; flex-wrap: wrap;
        }
        .filter-label { font-size: .85rem; font-weight: 600; color: var(--muted); white-space: nowrap; }
        .filter-select {
            border: 1.5px solid var(--border); border-radius: var(--radius-sm);
            padding: 9px 14px; font-size: .88rem; color: var(--dark);
            background: var(--cream); min-width: 180px; outline: none;
            transition: border-color .2s;
        }
        .filter-select:focus { border-color: var(--gold); }
        .btn-filter {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff; border: none; border-radius: var(--radius-sm);
            padding: 9px 20px; font-size: .88rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px; transition: opacity .2s;
        }
        .btn-filter:hover { opacity: .88; }
        .btn-filter-clear {
            background: transparent; color: var(--muted); border: 1.5px solid var(--border);
            border-radius: var(--radius-sm); padding: 9px 16px; font-size: .85rem;
            font-weight: 500; cursor: pointer; text-decoration: none; transition: all .2s;
        }
        .btn-filter-clear:hover { border-color: var(--gold); color: var(--gold-dark); text-decoration: none; }

        /* ─── PRODUCT GRID ─── */
        .product-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 22px; }
        @media(max-width:1100px){ .product-grid { grid-template-columns: repeat(3,1fr); } }
        @media(max-width:768px){ .product-grid { grid-template-columns: repeat(2,1fr); } }
        @media(max-width:480px){ .product-grid { grid-template-columns: 1fr; } }

        .product-card {
            background: #fff; border: 1px solid var(--border); border-radius: var(--radius);
            overflow: hidden; transition: transform .25s, box-shadow .25s;
            display: flex; flex-direction: column;
        }
        .product-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: var(--gold-light); }
        .product-img-wrap { position: relative; overflow: hidden; }
        .product-card > img,
        .product-card > .product-thumb {
            display: block; width: min(100%, 220px); height: 180px; margin: 0 auto; object-fit: cover;
        }
        .product-img-wrap img, .product-img-placeholder {
            width: min(100%, 220px); height: 180px; margin: 0 auto; object-fit: cover;
            transition: transform .35s;
        }
        .product-img-placeholder {
            background: linear-gradient(135deg, #f5efe0, #ede3cc);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center; gap: 8px;
            color: var(--gold); font-size: .8rem; font-weight: 500;
        }
        .product-img-placeholder i { font-size: 2rem; opacity: .5; }
        .product-card:hover .product-img-wrap img { transform: scale(1.06); }
        .product-badge-wrap { position: absolute; top: 10px; left: 10px; display: flex; gap: 6px; flex-wrap: wrap; }
        .badge-sale {
            background: linear-gradient(135deg, #e53e3e, #c0392b);
            color: #fff; font-size: .7rem; font-weight: 700;
            padding: 3px 8px; border-radius: 999px; letter-spacing: .3px;
        }
        .badge-cat {
            background: rgba(253,249,242,.92); color: var(--gold-dark);
            font-size: .7rem; font-weight: 600;
            padding: 3px 8px; border-radius: 999px;
            border: 1px solid var(--gold-light);
        }
        .product-body { padding: 16px 18px 18px; flex: 1; display: flex; flex-direction: column; }
        .product-cat { font-size: .72rem; font-weight: 600; color: var(--gold); letter-spacing: .5px; text-transform: uppercase; margin-bottom: 6px; }
        .product-name { font-family: 'Playfair Display', serif; font-size: .95rem; font-weight: 600; color: var(--dark); margin-bottom: 8px; line-height: 1.4; flex: 1; }
        .product-desc { font-size: .78rem; color: var(--muted); margin-bottom: 10px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-colors { display: flex; gap: 4px; margin-bottom: 12px; flex-wrap: wrap; }
        .color-dot { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,.12); cursor: default; title: attr(title); }
        .product-price-row { display: flex; align-items: baseline; gap: 8px; margin-bottom: 14px; }
        .price-sale { font-family: 'Playfair Display', serif; font-size: 1.15rem; font-weight: 700; color: var(--gold-dark); }
        .price-old { font-size: .82rem; color: #aaa; text-decoration: line-through; }
        .btn-detail {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: #fff; border: none; border-radius: var(--radius-sm);
            padding: 10px 16px; font-size: .85rem; font-weight: 600;
            text-decoration: none; text-align: center; transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            box-shadow: 0 4px 12px rgba(201,168,76,.3);
        }
        .btn-detail:hover { opacity: .9; color: #fff; text-decoration: none; transform: translateY(-1px); }

        /* ─── PAGINATION ─── */
        .pagination-wrap { display: flex; justify-content: center; margin-top: 40px; }
        .pagination { display: flex; gap: 4px; list-style: none; flex-wrap: wrap; }
        .pagination li span, .pagination li a {
            display: flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: var(--radius-sm);
            font-size: .88rem; font-weight: 600;
            border: 1.5px solid var(--border); background: #fff;
            color: var(--dark); text-decoration: none; transition: all .2s;
        }
        .pagination li.active span { background: var(--gold); border-color: var(--gold); color: #fff; }
        .pagination li a:hover { border-color: var(--gold); color: var(--gold-dark); }
        .pagination li.disabled span { color: #ccc; cursor: not-allowed; }

        /* ─── AUTH CARD ─── */
        .auth-shell {
            min-height: calc(100vh - 70px);
            display: flex; align-items: center; justify-content: center; padding: 40px 16px;
        }
        .auth-card {
            width: 100%; max-width: 460px;
            background: #ffffff;
            border: 1px solid #d9cbb3;
            border-radius: 20px;
            padding: 42px;
            box-shadow: 0 16px 40px rgba(26,18,8,.16);
            animation: fadeUp .4s ease;
        }
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .auth-icon {
            width: 60px; height: 60px; border-radius: 50%;
            background: #6b4e16;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.4rem; margin: 0 auto 20px;
        }
        .auth-card h2 { font-family: 'Playfair Display', serif; font-size: 1.7rem; font-weight: 800; text-align: center; color: #111111; margin-bottom: 8px; }
        .auth-card .sub { text-align: center; color: #333333; font-size: .95rem; margin-bottom: 28px; line-height: 1.55; }
        .field-label { font-size: .9rem; font-weight: 700; color: #111111; margin-bottom: 6px; display: block; }
        .field-input {
            width: 100%; border: 2px solid #5c5348; border-radius: var(--radius-sm);
            padding: 12px 14px; font-size: .95rem; color: #111111;
            background: #ffffff; outline: none; transition: border-color .2s, box-shadow .2s;
            font-family: 'Inter', sans-serif;
        }
        select.field-input { cursor: pointer; appearance: auto; }
        .field-input:focus { border-color: #6b4e16; box-shadow: 0 0 0 3px rgba(107,78,22,.2); }
        .field-group { margin-bottom: 18px; }
        .field-error { font-size: .78rem; color: #b42318; margin-top: 4px; }
        .btn-auth {
            width: 100%; background: #6b4e16;
            color: #ffffff; border: none; border-radius: var(--radius-sm);
            padding: 14px; font-size: 1rem; font-weight: 800; cursor: pointer;
            box-shadow: 0 4px 14px rgba(26,18,8,.2); transition: all .2s;
            font-family: 'Inter', sans-serif;
        }
        .btn-auth:hover { opacity: .9; transform: translateY(-1px); box-shadow: 0 6px 18px rgba(201,168,76,.45); }
        .auth-link { text-align: center; margin-top: 18px; font-size: .87rem; color: #4a4338; }
        .auth-link a { color: #7a5c1e; font-weight: 700; text-decoration: none; }
        .auth-link a:hover { text-decoration: underline; }

        .form-group { margin-bottom: 18px; }
        .form-group label,
        label.small,
        label.font-weight-bold {
            display: block;
            font-size: .9rem;
            font-weight: 700;
            color: #1a1208;
            margin-bottom: 6px;
        }
        .form-control {
            width: 100%;
            border: 1.5px solid #b7a78c;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: .95rem;
            color: #1a1208;
            background: #fff;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        .form-control:focus {
            border-color: #9e7b2f;
            box-shadow: 0 0 0 3px rgba(158,123,47,.18);
        }
        .btn-shop {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: linear-gradient(135deg, #c9a84c, #7a5c1e);
            color: #fff !important;
            border: none;
            border-radius: 8px;
            padding: 12px 22px;
            font-size: .95rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
        }
        .btn-shop:hover { opacity: .92; color: #fff !important; text-decoration: none; }
        .btn-shop.btn-block { width: 100%; }
        .text-muted { color: #5a5146 !important; }
        .text-center { text-align: center; }
        .text-danger { color: #b42318; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 4px !important; }
        .mb-2 { margin-bottom: 8px !important; }
        .mb-3 { margin-bottom: 16px !important; }
        .mb-4 { margin-bottom: 24px !important; }
        .mt-2 { margin-top: 8px !important; }
        .mt-3 { margin-top: 16px !important; }
        .small { font-size: .85rem; }
        .d-flex { display: flex; }
        .justify-content-center { justify-content: center; }
        .align-items-end { align-items: flex-end; }
        .form-row { display: flex; flex-wrap: wrap; gap: 16px; }
        .col-md-4 { flex: 1 1 240px; }
        .col-md-2 { flex: 0 0 auto; }
        .price-tag {
            font-family: 'Playfair Display', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #7a5c1e;
        }
        .auth-card p,
        .auth-card .text-muted {
            color: #4a4338 !important;
            font-size: .92rem;
            line-height: 1.55;
        }
        .auth-card label { color: #1a1208; font-weight: 700; }
        .auth-card a { color: #7a5c1e; font-weight: 700; }

        /* ─── PRODUCT DETAIL ─── */
        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; margin-top: 24px; }
        @media(max-width:768px){ .detail-grid { grid-template-columns: 1fr; } }
        .detail-img {
            border-radius: var(--radius); overflow: hidden;
            border: 1px solid var(--border); background: linear-gradient(135deg,#f5efe0,#ede3cc);
            aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
        }
        .detail-img img { width: 100%; height: 100%; object-fit: cover; }
        .detail-img-placeholder { color: var(--gold); opacity: .35; font-size: 5rem; }
        .detail-cat { font-size: .8rem; font-weight: 700; color: var(--gold); letter-spacing: .8px; text-transform: uppercase; margin-bottom: 12px; }
        .detail-name { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 800; color: var(--dark); margin-bottom: 16px; line-height: 1.25; }
        .detail-price-sale { font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--gold-dark); }
        .detail-price-old { font-size: 1rem; color: #aaa; text-decoration: line-through; margin-left: 8px; }
        .detail-divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        .detail-desc { font-size: .95rem; color: var(--muted); line-height: 1.75; margin-bottom: 20px; }
        .detail-colors-title { font-size: .85rem; font-weight: 700; color: var(--dark); margin-bottom: 10px; }
        .detail-colors { display: flex; gap: 10px; flex-wrap: wrap; }
        .detail-color-chip {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--cream); border: 1.5px solid var(--border);
            border-radius: 999px; padding: 7px 14px;
            font-size: .82rem; font-weight: 500; color: var(--dark);
            transition: border-color .2s;
        }
        .detail-color-chip:hover { border-color: var(--gold); }
        .detail-color-dot { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,.1); }
        .detail-stock { font-size: .8rem; color: var(--muted); }
        .voucher-box {
            background: linear-gradient(135deg, rgba(201,168,76,.08), rgba(201,168,76,.04));
            border: 1.5px dashed var(--gold-light); border-radius: var(--radius-sm);
            padding: 14px 18px; margin-bottom: 20px;
            display: flex; align-items: center; gap: 12px;
        }
        .voucher-icon { font-size: 1.2rem; color: var(--gold); }
        .voucher-text { font-size: .85rem; }
        .voucher-code { font-weight: 700; color: var(--gold-dark); font-family: monospace; font-size: .9rem; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 6px;
            color: var(--muted); text-decoration: none; font-size: .88rem; font-weight: 500;
            margin-bottom: 24px; transition: color .2s;
        }
        .btn-back:hover { color: var(--gold-dark); text-decoration: none; }

        /* ─── FOOTER ─── */
        .shop-footer {
            background: var(--dark); color: rgba(255,255,255,.55);
            text-align: center; padding: 28px;
            font-size: .83rem; margin-top: 64px;
        }
        .shop-footer strong { color: var(--gold-light); }

        /* ─── EMPTY STATE ─── */
        .empty-state {
            text-align: center; padding: 64px 24px; color: var(--muted);
        }
        .empty-state i { font-size: 3.5rem; color: var(--gold-light); margin-bottom: 16px; display: block; }
        .empty-state h3 { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--dark); margin-bottom: 8px; }

        /* ─── SECTION HEADING ─── */
        .section-heading {
            text-align: center; margin-bottom: 36px;
        }
        .section-heading .eyebrow {
            display: inline-block; font-size: .75rem; font-weight: 700;
            color: var(--gold); letter-spacing: 2px; text-transform: uppercase; margin-bottom: 10px;
        }
        .section-heading h2 { font-family: 'Playfair Display', serif; font-size: 1.9rem; font-weight: 800; color: var(--dark); }

        .checkout-grid { display: grid; grid-template-columns: 1.2fr .8fr; gap: 24px; }
        @media(max-width:900px){ .checkout-grid { grid-template-columns: 1fr; } }
        .checkout-card {
            background: #fff; border: 1px solid #d9cbb3; border-radius: 16px;
            padding: 24px; box-shadow: var(--shadow-sm);
        }
        .checkout-card h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: 16px; color: #1a1208; }
        .checkout-table { width: 100%; border-collapse: collapse; }
        .checkout-table th { text-align: left; font-size: .78rem; text-transform: uppercase; color: #5a5146; padding: 10px 8px; border-bottom: 1px solid #eee; }
        .checkout-table td { padding: 14px 8px; border-bottom: 1px solid #f3eee6; vertical-align: middle; color: #1a1208; }
        .checkout-summary { display: flex; justify-content: space-between; align-items: center; gap: 16px; margin-top: 18px; flex-wrap: wrap; }
        .qty-form { display: flex; gap: 8px; align-items: center; }
        .order-line { display: flex; justify-content: space-between; gap: 12px; margin-bottom: 10px; color: #1a1208; }
        .total-line { font-size: 1.05rem; }
        .pay-option { display: flex; align-items: center; gap: 8px; margin-bottom: 10px; font-weight: 600; color: #1a1208; }
        .status-pill { display: inline-block; background: #f3eee6; color: #7a5c1e; border-radius: 999px; padding: 4px 10px; font-size: .75rem; font-weight: 700; margin-left: 4px; }
        .variant-pick { display: flex; flex-direction: column; gap: 8px; margin-top: 8px; }
        .variant-option { display: flex; align-items: center; gap: 8px; font-weight: 600; color: #1a1208; }
        .color-dot { width: 14px; height: 14px; border-radius: 50%; display: inline-block; border: 1px solid rgba(0,0,0,.15); vertical-align: middle; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="shop-nav">
    <div class="nav-inner">
        <a class="nav-brand" href="{{ route('welcome') }}">
            <div class="nav-brand-icon"><i class="fas fa-gem"></i></div>
            <span class="nav-brand-text">Trang Sức <span>Của Huy</span></span>
        </a>

        <div class="nav-links">
            <a href="{{ route('welcome') }}" class="{{ request()->routeIs('welcome') ? 'active' : '' }}">Trang chủ</a>
            @auth
                @if(auth()->user()->hasVerifiedEmail() || auth()->user()->isAdmin())
                    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Bộ sưu tập</a>
                    <a href="{{ route('user.cart.index') }}" class="{{ request()->routeIs('user.cart.*') ? 'active' : '' }}">
                        Giỏ hàng
                        @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                        @if($cartCount > 0) ({{ $cartCount }}) @endif
                    </a>
                    <a href="{{ route('user.orders.index') }}" class="{{ request()->routeIs('user.orders.*') ? 'active' : '' }}">Đơn hàng</a>
                    <a href="{{ route('user.chat.index') }}" class="{{ request()->routeIs('user.chat.*') ? 'active' : '' }}">Chat</a>
                    <a href="{{ route('user.ai-chat.index') }}" class="{{ request()->routeIs('user.ai-chat.*') ? 'active' : '' }}">Chat AI</a>
                @else
                    <a href="{{ route('verification.notice') }}">Bộ sưu tập</a>
                @endif
            @else
                <a href="{{ route('login') }}">Bộ sưu tập</a>
            @endauth
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"><i class="fas fa-shield-alt"></i> Quản trị</a>
                @endif
            @endauth
        </div>

        <div class="nav-actions">
            @guest
                <a href="{{ route('login') }}" class="btn-nav-outline">Đăng nhập</a>
                <a href="{{ route('register') }}" class="btn-nav-solid"><i class="fas fa-user-plus"></i> Đăng ký</a>
            @else
                <span class="nav-user-info"><i class="fas fa-user-circle" style="color:var(--gold);"></i> {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-nav-outline">Đăng xuất</button>
                </form>
            @endguest
        </div>
    </div>
</nav>

@if(session('success') || session('error') || session('warning'))
<div class="flash-wrap">
    @if(session('success'))<div class="alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-danger"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>@endif
    @if(session('warning'))<div class="alert-danger" style="background:#fff7ed;border-color:#fdba74;color:#9a3412;"><i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}</div>@endif
</div>
@endif

<div class="container-shop">
    @yield('content')
</div>

<footer class="shop-footer">
    © {{ date('Y') }} <strong>Trang Sức Của Huy</strong> – Vẻ đẹp đích thực từ từng chi tiết.
</footer>

@stack('scripts')
</body>
</html>
