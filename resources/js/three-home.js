import * as THREE from 'three';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

class Home3D {
    constructor() {
        this.container = document.querySelector('#webgl-container');
        if (!this.container) return;

        this.width = window.innerWidth;
        this.height = window.innerHeight;

        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.FogExp2(0x0f172a, 0.002); // Dark mode fog

        this.camera = new THREE.PerspectiveCamera(70, this.width / this.height, 0.1, 1000);
        this.camera.position.z = 100;

        this.renderer = new THREE.WebGLRenderer({
            antialias: true,
            alpha: true
        });
        this.renderer.setSize(this.width, this.height);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.container.appendChild(this.renderer.domElement);

        this.images = [
            '/images/sapa_3d.jpg',
            '/images/halong_3d.jpg',
            '/images/hoian_3d.jpg'
        ];
        
        this.currentIndex = 0;
        this.textures = [];
        this.meshes = [];

        this.time = 0;
        this.mouse = new THREE.Vector2(0, 0);
        this.targetMouse = new THREE.Vector2(0, 0);

        this.init();
    }

    async init() {
        try {
            // Load textures
            const textureLoader = new THREE.TextureLoader();
            for (let img of this.images) {
                try {
                    const texture = await textureLoader.loadAsync(img);
                    this.textures.push(texture);
                } catch (e) {
                    console.error('Failed to load texture:', img, e);
                }
            }

            this.addObjects();
            this.addParticles();
            this.addLights();
            this.addEvents();
            
            // GSAP Scroll Animations for HTML overlay
            this.initGSAP();

            this.render();
        } catch (error) {
            console.error('Critical error in 3D initialization:', error);
        } finally {
            // Remove preloader always
            const preloader = document.getElementById('preloader-3d');
            if (preloader) {
                gsap.to(preloader, { opacity: 0, duration: 1, onComplete: () => preloader.remove() });
            }
        }
    }

    addObjects() {
        const geometry = new THREE.PlaneGeometry(350, 200, 64, 64);
        
        this.textures.forEach((texture, index) => {
            const material = new THREE.MeshStandardMaterial({
                map: texture,
                transparent: true,
                opacity: index === 0 ? 1 : 0,
                roughness: 0.8,
                metalness: 0.2,
                wireframe: false
            });

            const mesh = new THREE.Mesh(geometry, material);
            mesh.position.z = index === 0 ? 0 : -50; // Push others back
            this.scene.add(mesh);
            this.meshes.push(mesh);
        });
    }

    addParticles() {
        const geometry = new THREE.BufferGeometry();
        const count = 3000;
        const positions = new Float32Array(count * 3);

        for(let i = 0; i < count; i++) {
            positions[i*3] = (Math.random() - 0.5) * 400; // x
            positions[i*3+1] = (Math.random() - 0.5) * 400; // y
            positions[i*3+2] = (Math.random() - 0.5) * 400 - 50; // z
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const material = new THREE.PointsMaterial({
            size: 0.8,
            color: 0x10b981, // Neon green/teal glow
            transparent: true,
            opacity: 0.6,
            depthWrite: false
        });

        this.particles = new THREE.Points(geometry, material);
        this.scene.add(this.particles);
    }

    addLights() {
        const ambient = new THREE.AmbientLight(0xffffff, 0.4);
        this.scene.add(ambient);

        this.dirLight = new THREE.DirectionalLight(0xffffff, 1.5);
        this.dirLight.position.set(0, 50, 100);
        this.scene.add(this.dirLight);
        
        const pointLight = new THREE.PointLight(0x10b981, 2, 200);
        pointLight.position.set(50, 0, 50);
        this.scene.add(pointLight);
    }

    addEvents() {
        window.addEventListener('resize', this.resize.bind(this));
        window.addEventListener('mousemove', (e) => {
            this.targetMouse.x = (e.clientX / window.innerWidth) * 2 - 1;
            this.targetMouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
        });

        // Listen for carousel button clicks to swap textures
        document.querySelectorAll('.tour-slide-btn').forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                this.changeSlide(idx);
            });
        });
    }
    
    changeSlide(newIndex) {
        if(newIndex === this.currentIndex || newIndex >= this.meshes.length) return;
        
        const currentMesh = this.meshes[this.currentIndex];
        const nextMesh = this.meshes[newIndex];
        
        // GSAP transition
        gsap.to(currentMesh.material, { opacity: 0, duration: 1.5, ease: "power2.inOut" });
        gsap.to(currentMesh.position, { z: -50, duration: 1.5, ease: "power2.inOut" });
        
        nextMesh.position.z = -50;
        gsap.to(nextMesh.material, { opacity: 1, duration: 1.5, ease: "power2.inOut" });
        gsap.to(nextMesh.position, { z: 0, duration: 1.5, ease: "power2.inOut" });
        
        this.currentIndex = newIndex;
        
        // Update UI
        document.querySelectorAll('.tour-slide-btn').forEach((btn, idx) => {
            if(idx === newIndex) btn.classList.add('active-slide');
            else btn.classList.remove('active-slide');
        });
    }

    initGSAP() {
        // Animate HTML elements on load
        const tl = gsap.timeline();
        tl.from(".hero-title", { y: 50, opacity: 0, duration: 1, delay: 0.5, stagger: 0.2, ease: "power3.out" })
          .from(".hero-subtitle", { y: 30, opacity: 0, duration: 0.8 }, "-=0.6")
          .from(".hero-actions", { y: 20, opacity: 0, duration: 0.8 }, "-=0.6")
          .from(".tour-controls", { opacity: 0, duration: 1 }, "-=0.4");
          
        // Scroll fade for hero
        if(document.querySelector('.hero-section')) {
            gsap.to(".hero-overlay", {
                scrollTrigger: {
                    trigger: ".hero-section",
                    start: "top top",
                    end: "bottom top",
                    scrub: true
                },
                opacity: 0,
                y: -100
            });
        }
    }

    resize() {
        this.width = window.innerWidth;
        this.height = window.innerHeight;
        this.renderer.setSize(this.width, this.height);
        this.camera.aspect = this.width / this.height;
        this.camera.updateProjectionMatrix();
    }

    render() {
        this.time += 0.05;

        // Smooth mouse follow
        this.mouse.lerp(this.targetMouse, 0.05);

        // Parallax effect on meshes
        this.meshes.forEach((mesh, index) => {
            // Apply a slight wave effect to the vertices
            const positionAttribute = mesh.geometry.attributes.position;
            const vertex = new THREE.Vector3();
            for (let i = 0; i < positionAttribute.count; i++) {
                vertex.fromBufferAttribute(positionAttribute, i);
                // Distort Z based on sine wave and mouse
                vertex.z = Math.sin(vertex.x * 0.05 + this.time + index) * 3 + Math.cos(vertex.y * 0.05 + this.time) * 3;
                positionAttribute.setXYZ(i, vertex.x, vertex.y, vertex.z);
            }
            positionAttribute.needsUpdate = true;
            
            // Mouse parallax
            if(index === this.currentIndex) {
                mesh.rotation.y = this.mouse.x * 0.1;
                mesh.rotation.x = -this.mouse.y * 0.1;
            }
        });

        // Rotate particles slowly
        if(this.particles) {
            this.particles.rotation.y = this.time * 0.05;
            this.particles.rotation.x = this.time * 0.02;
            
            // Mouse parallax for particles
            this.particles.position.x = -this.mouse.x * 20;
            this.particles.position.y = -this.mouse.y * 20;
        }
        
        // Move directional light
        if(this.dirLight) {
            this.dirLight.position.x = this.mouse.x * 100;
            this.dirLight.position.y = this.mouse.y * 100 + 50;
        }

        this.renderer.render(this.scene, this.camera);
        requestAnimationFrame(this.render.bind(this));
    }
}

// Initialize only when DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    new Home3D();
});
