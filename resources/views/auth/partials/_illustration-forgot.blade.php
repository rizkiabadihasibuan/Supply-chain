{{-- Forgot Password Illustration --}}
<svg viewBox="0 0 300 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 280px; height: auto;">
    <!-- Outer ring -->
    <circle cx="150" cy="120" r="85" stroke="rgba(255,255,255,.12)" stroke-width="1.5" fill="none" stroke-dasharray="6 4"/>

    <!-- Lock body -->
    <rect x="120" y="110" width="60" height="50" rx="10" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.3)" stroke-width="2"/>
    
    <!-- Lock shackle -->
    <path d="M132,112 L132,92 Q132,72 150,72 Q168,72 168,92 L168,112" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="3" stroke-linecap="round"/>
    
    <!-- Keyhole -->
    <circle cx="150" cy="132" r="8" fill="rgba(255,255,255,.2)" stroke="rgba(255,255,255,.3)" stroke-width="1.5"/>
    <rect x="147" y="136" width="6" height="12" rx="2" fill="rgba(255,255,255,.2)"/>

    <!-- Question mark -->
    <circle cx="200" cy="85" r="16" fill="rgba(251,191,36,.4)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="200" y="92" text-anchor="middle" fill="white" font-size="16" font-weight="700">?</text>

    <!-- Key icon floating -->
    <g transform="translate(85, 155) rotate(-30)">
        <circle cx="12" cy="12" r="10" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="2"/>
        <line x1="22" y1="12" x2="42" y2="12" stroke="rgba(255,255,255,.25)" stroke-width="2"/>
        <line x1="36" y1="12" x2="36" y2="18" stroke="rgba(255,255,255,.25)" stroke-width="2"/>
        <line x1="42" y1="12" x2="42" y2="20" stroke="rgba(255,255,255,.25)" stroke-width="2"/>
    </g>

    <!-- Floating particles -->
    <circle cx="90" cy="75" r="3" fill="rgba(255,255,255,.2)">
        <animate attributeName="opacity" values="0.2;0.6;0.2" dur="2s" repeatCount="indefinite"/>
    </circle>
    <circle cx="215" cy="160" r="4" fill="rgba(255,255,255,.15)">
        <animate attributeName="opacity" values="0.15;0.5;0.15" dur="2.5s" repeatCount="indefinite"/>
    </circle>

    <!-- Pulse ring -->
    <circle cx="150" cy="130" r="35" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1">
        <animate attributeName="r" from="35" to="55" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" from="0.4" to="0" dur="2.5s" repeatCount="indefinite"/>
    </circle>
</svg>
