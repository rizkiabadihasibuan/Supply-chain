{{-- Supply Chain Illustration – Default (Login/Register) --}}
<svg viewBox="0 0 360 300" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 340px; height: auto;">
    <!-- Globe -->
    <circle cx="180" cy="140" r="90" stroke="rgba(255,255,255,.15)" stroke-width="1.5" fill="none"/>
    <circle cx="180" cy="140" r="70" stroke="rgba(255,255,255,.1)" stroke-width="1" fill="none" stroke-dasharray="4 6"/>
    <ellipse cx="180" cy="140" rx="90" ry="30" stroke="rgba(255,255,255,.12)" stroke-width="1" fill="none"/>
    <ellipse cx="180" cy="140" rx="40" ry="90" stroke="rgba(255,255,255,.1)" stroke-width="1" fill="none"/>

    <!-- Nodes (supply chain points) -->
    <circle cx="110" cy="100" r="16" fill="rgba(255,255,255,.12)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="110" y="105" text-anchor="middle" fill="white" font-size="12" font-weight="600">🏭</text>
    
    <circle cx="250" cy="100" r="16" fill="rgba(255,255,255,.12)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="250" y="105" text-anchor="middle" fill="white" font-size="12" font-weight="600">🚢</text>
    
    <circle cx="180" cy="60" r="16" fill="rgba(255,255,255,.12)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="180" y="65" text-anchor="middle" fill="white" font-size="12" font-weight="600">📦</text>
    
    <circle cx="130" cy="195" r="16" fill="rgba(255,255,255,.12)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="130" y="200" text-anchor="middle" fill="white" font-size="12" font-weight="600">🏪</text>
    
    <circle cx="230" cy="195" r="16" fill="rgba(255,255,255,.12)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <text x="230" y="200" text-anchor="middle" fill="white" font-size="12" font-weight="600">📊</text>

    <!-- Connections -->
    <line x1="126" y1="100" x2="164" y2="72" stroke="rgba(255,255,255,.2)" stroke-width="1" stroke-dasharray="3 3">
        <animate attributeName="stroke-dashoffset" from="0" to="-6" dur="1.5s" repeatCount="indefinite"/>
    </line>
    <line x1="196" y1="72" x2="234" y2="100" stroke="rgba(255,255,255,.2)" stroke-width="1" stroke-dasharray="3 3">
        <animate attributeName="stroke-dashoffset" from="0" to="-6" dur="1.5s" repeatCount="indefinite"/>
    </line>
    <line x1="250" y1="116" x2="240" y2="179" stroke="rgba(255,255,255,.2)" stroke-width="1" stroke-dasharray="3 3">
        <animate attributeName="stroke-dashoffset" from="0" to="-6" dur="1.8s" repeatCount="indefinite"/>
    </line>
    <line x1="110" y1="116" x2="120" y2="179" stroke="rgba(255,255,255,.2)" stroke-width="1" stroke-dasharray="3 3">
        <animate attributeName="stroke-dashoffset" from="0" to="-6" dur="1.8s" repeatCount="indefinite"/>
    </line>
    <line x1="146" y1="195" x2="214" y2="195" stroke="rgba(255,255,255,.2)" stroke-width="1" stroke-dasharray="3 3">
        <animate attributeName="stroke-dashoffset" from="0" to="-6" dur="2s" repeatCount="indefinite"/>
    </line>

    <!-- Center shield icon -->
    <circle cx="180" cy="140" r="24" fill="rgba(37,99,235,.6)" stroke="rgba(255,255,255,.3)" stroke-width="2"/>
    <text x="180" y="147" text-anchor="middle" fill="white" font-size="18" font-weight="700">🛡️</text>
    
    <!-- Pulse ring animation -->
    <circle cx="180" cy="140" r="24" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="1.5">
        <animate attributeName="r" from="24" to="45" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" from="0.6" to="0" dur="2.5s" repeatCount="indefinite"/>
    </circle>
</svg>
