{{-- Email Verification Illustration --}}
<svg viewBox="0 0 300 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 280px; height: auto;">
    <!-- Outer ring -->
    <circle cx="150" cy="120" r="85" stroke="rgba(255,255,255,.12)" stroke-width="1.5" fill="none" stroke-dasharray="6 4"/>
    <circle cx="150" cy="120" r="65" stroke="rgba(255,255,255,.08)" stroke-width="1" fill="none"/>

    <!-- Envelope body -->
    <rect x="90" y="90" width="120" height="75" rx="8" fill="rgba(255,255,255,.1)" stroke="rgba(255,255,255,.3)" stroke-width="2"/>
    
    <!-- Envelope flap -->
    <path d="M90,92 L150,132 L210,92" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="2" stroke-linejoin="round"/>
    
    <!-- Mail lines -->
    <line x1="110" y1="138" x2="145" y2="138" stroke="rgba(255,255,255,.15)" stroke-width="2" stroke-linecap="round"/>
    <line x1="110" y1="148" x2="165" y2="148" stroke="rgba(255,255,255,.1)" stroke-width="2" stroke-linecap="round"/>

    <!-- Check badge -->
    <circle cx="200" cy="90" r="18" fill="rgba(34,197,94,.5)" stroke="rgba(255,255,255,.4)" stroke-width="2"/>
    <path d="M191,90 L197,96 L209,84" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>

    <!-- Floating dots -->
    <circle cx="80" cy="70" r="3" fill="rgba(255,255,255,.2)">
        <animate attributeName="opacity" values="0.2;0.6;0.2" dur="2s" repeatCount="indefinite"/>
    </circle>
    <circle cx="220" cy="160" r="4" fill="rgba(255,255,255,.15)">
        <animate attributeName="opacity" values="0.15;0.5;0.15" dur="2.5s" repeatCount="indefinite"/>
    </circle>
    <circle cx="120" cy="55" r="2.5" fill="rgba(255,255,255,.18)">
        <animate attributeName="opacity" values="0.18;0.55;0.18" dur="1.8s" repeatCount="indefinite"/>
    </circle>

    <!-- Pulse around envelope -->
    <rect x="90" y="90" width="120" height="75" rx="8" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1">
        <animate attributeName="opacity" from="0.4" to="0" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="x" from="90" to="80" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="y" from="90" to="80" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="width" from="120" to="140" dur="2s" repeatCount="indefinite"/>
        <animate attributeName="height" from="75" to="95" dur="2s" repeatCount="indefinite"/>
    </rect>
</svg>
