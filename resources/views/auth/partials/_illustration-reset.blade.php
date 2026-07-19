{{-- Reset Password Illustration --}}
<svg viewBox="0 0 300 260" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 100%; max-width: 280px; height: auto;">
    <!-- Outer ring -->
    <circle cx="150" cy="120" r="85" stroke="rgba(255,255,255,.12)" stroke-width="1.5" fill="none" stroke-dasharray="6 4"/>

    <!-- Shield body -->
    <path d="M150,60 L195,82 L195,128 Q195,168 150,190 Q105,168 105,128 L105,82 Z" fill="rgba(255,255,255,.08)" stroke="rgba(255,255,255,.25)" stroke-width="2"/>
    
    <!-- Inner shield -->
    <path d="M150,78 L183,94 L183,128 Q183,158 150,175 Q117,158 117,128 L117,94 Z" fill="rgba(37,99,235,.3)" stroke="rgba(255,255,255,.2)" stroke-width="1.5"/>

    <!-- Refresh / reset arrow -->
    <g transform="translate(150,125)">
        <path d="M-12,-15 A18,18 0 1,1 -18,3" fill="none" stroke="rgba(255,255,255,.6)" stroke-width="2.5" stroke-linecap="round"/>
        <polygon points="-21,3 -15,3 -18,-5" fill="rgba(255,255,255,.6)"/>
    </g>

    <!-- Success check badge -->
    <circle cx="198" cy="80" r="15" fill="rgba(34,197,94,.45)" stroke="rgba(255,255,255,.35)" stroke-width="1.5"/>
    <path d="M190,80 L195,85 L206,74" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>

    <!-- Floating dots -->
    <circle cx="85" cy="80" r="3" fill="rgba(255,255,255,.2)">
        <animate attributeName="opacity" values="0.2;0.6;0.2" dur="2s" repeatCount="indefinite"/>
    </circle>
    <circle cx="220" cy="165" r="4" fill="rgba(255,255,255,.15)">
        <animate attributeName="opacity" values="0.15;0.5;0.15" dur="2.5s" repeatCount="indefinite"/>
    </circle>
    <circle cx="110" cy="185" r="2.5" fill="rgba(255,255,255,.18)">
        <animate attributeName="opacity" values="0.18;0.55;0.18" dur="1.8s" repeatCount="indefinite"/>
    </circle>

    <!-- Pulse ring -->
    <circle cx="150" cy="125" r="30" fill="none" stroke="rgba(255,255,255,.15)" stroke-width="1">
        <animate attributeName="r" from="30" to="50" dur="2.5s" repeatCount="indefinite"/>
        <animate attributeName="opacity" from="0.4" to="0" dur="2.5s" repeatCount="indefinite"/>
    </circle>
</svg>
