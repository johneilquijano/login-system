<!-- Feedback Highlight Component -->
<!-- Renders a visual pin at the location where user clicked when reporting feedback -->
<script>
    // Check if we have feedback highlight parameters
    const urlParams = new URLSearchParams(window.location.search);
    const feedbackId = urlParams.get('feedback_id');
    const pageX = parseInt(urlParams.get('page_x'), 10);
    const pageY = parseInt(urlParams.get('page_y'), 10);
    const isSuperAdmin = {{ Auth::check() && Auth::user()->is_super_admin ? 'true' : 'false' }};

    if (feedbackId && !isNaN(pageX) && !isNaN(pageY)) {
        // Wait for DOM to be fully loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', renderHighlight);
        } else {
            renderHighlight();
        }
    }

    function renderHighlight() {
        // Create banner at top of page
        const banner = document.createElement('div');
        banner.id = 'feedback-highlight-banner';
        banner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: linear-gradient(135deg, rgb(59, 130, 246), rgb(37, 99, 235));
            color: white;
            padding: 12px 20px;
            z-index: 50000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
        `;

        const messageDiv = document.createElement('div');
        messageDiv.style.cssText = 'flex: 1; display: flex; align-items: center; gap: 12px;';
        messageDiv.innerHTML = `
            <svg style="width: 20px; height: 20px; flex-shrink: 0;" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"></path>
            </svg>
            <span>Viewing feedback location - scroll to see the highlighted element</span>
        `;

        const closeButton = document.createElement('button');
        closeButton.style.cssText = `
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 6px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            margin-left: 12px;
            flex-shrink: 0;
        `;
        closeButton.textContent = '← Back to Feedback';
        closeButton.onmouseover = () => {
            closeButton.style.background = 'rgba(255, 255, 255, 0.3)';
        };
        closeButton.onmouseout = () => {
            closeButton.style.background = 'rgba(255, 255, 255, 0.2)';
        };
        closeButton.onclick = () => {
            // Get the feedback ID from URL and navigate back
            const fbId = new URLSearchParams(window.location.search).get('feedback_id');
            const baseUrl = isSuperAdmin ? '/super-admin/feedback' : '/admin/feedback';
            if (fbId) {
                window.location.href = `${baseUrl}/${fbId}`;
            } else {
                window.location.href = baseUrl;
            }
        };

        banner.appendChild(messageDiv);
        banner.appendChild(closeButton);
        document.body.insertBefore(banner, document.body.firstChild);

        // Add padding to body to account for banner
        document.body.style.paddingTop = '50px';

        // Auto-scroll to the click location with some padding
        const offset = 150; // Scroll to a point above the click for better visibility
        setTimeout(() => {
            window.scrollTo({
                top: Math.max(0, pageY - offset),
                left: Math.max(0, pageX - 100),
                behavior: 'smooth'
            });
        }, 300);

        // Create animated pin at click location (40px below the element)
        const pin = document.createElement('div');
        pin.id = 'feedback-highlight-pin';
        const pinOffsetY = pageY + 40; // Position 40px below the clicked element
        pin.style.cssText = `
            position: absolute;
            left: ${pageX}px;
            top: ${pinOffsetY}px;
            width: 40px;
            height: 40px;
            margin-left: -20px;
            margin-top: -20px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.8), rgba(59, 130, 246, 0.4));
            border: 3px solid rgba(59, 130, 246, 1);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 0 40px rgba(59, 130, 246, 0.3);
            pointer-events: none;
            z-index: 9999;
            animation: feedbackPulse 2s ease-in-out infinite;
        `;

        document.body.appendChild(pin);

        // Create the pulsing animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes feedbackPulse {
                0%, 100% {
                    transform: scale(1);
                    box-shadow: 0 0 20px rgba(59, 130, 246, 0.6), 0 0 40px rgba(59, 130, 246, 0.3);
                }
                50% {
                    transform: scale(1.2);
                    box-shadow: 0 0 30px rgba(59, 130, 246, 0.8), 0 0 60px rgba(59, 130, 246, 0.5);
                }
            }

            @keyframes feedbackFadeOut {
                0% {
                    opacity: 1;
                }
                100% {
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Auto-dismiss after 10 seconds
        setTimeout(() => {
            if (pin && pin.parentNode) {
                pin.style.animation = 'feedbackFadeOut 1s ease-out forwards';
                setTimeout(() => {
                    if (pin.parentNode) {
                        pin.parentNode.removeChild(pin);
                    }
                }, 1000);
            }
        }, 10000);

        // Add click handler to dismiss early
        pin.addEventListener('click', () => {
            pin.style.animation = 'feedbackFadeOut 0.5s ease-out forwards';
            setTimeout(() => {
                if (pin.parentNode) {
                    pin.parentNode.removeChild(pin);
                }
            }, 500);
        });

        // Create tooltip near pin
        const tooltip = document.createElement('div');
        tooltip.style.cssText = `
            position: fixed;
            left: ${pageX + 50}px;
            top: ${pageY - 30}px;
            background: white;
            border: 2px solid rgb(59, 130, 246);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            color: rgb(59, 130, 246);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            pointer-events: none;
            z-index: 10000;
            white-space: nowrap;
            animation: feedbackFadeOut 1s ease-out forwards 9s;
        `;
        tooltip.textContent = 'Feedback location (click to dismiss)';
        document.body.appendChild(tooltip);

        setTimeout(() => {
            if (tooltip.parentNode) {
                tooltip.parentNode.removeChild(tooltip);
            }
        }, 10000);
    }
</script>
