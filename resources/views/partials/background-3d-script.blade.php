<script type="module">
import * as THREE from 'https://esm.sh/three@0.160.0';
import { gsap } from 'https://esm.sh/gsap@3.12.4';
import { ScrollTrigger } from 'https://esm.sh/gsap@3.12.4/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

class Home3D {
    constructor() {
        this.container = document.querySelector('#webgl-container');
        if (!this.container) return;

        this.width = window.innerWidth;
        this.height = window.innerHeight;

        this.scene = new THREE.Scene();
        this.scene.fog = new THREE.FogExp2(0x0f172a, 0.002);

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
            this.initGSAP();
            this.render();
        } catch (error) {
            console.error('Critical error in 3D init:', error);
        } finally {
            const preloader = document.getElementById('preloader-3d');
            if (preloader) {
                gsap.to(preloader, { opacity: 0, duration: 1, delay: 0.5, onComplete: () => preloader.remove() });
            }
            const contentWrapper = document.getElementById('content-wrapper');
            if (contentWrapper) {
                gsap.to(contentWrapper, { opacity: 1, duration: 1.5, delay: 0.5, ease: "power2.out" });
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
            mesh.position.z = index === 0 ? 0 : -50;
            this.scene.add(mesh);
            this.meshes.push(mesh);
        });
    }

    addParticles() {
        const geometry = new THREE.BufferGeometry();
        const count = 3000;
        const positions = new Float32Array(count * 3);

        for(let i = 0; i < count; i++) {
            positions[i*3] = (Math.random() - 0.5) * 400; 
            positions[i*3+1] = (Math.random() - 0.5) * 400;
            positions[i*3+2] = (Math.random() - 0.5) * 400 - 50;
        }

        geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        const material = new THREE.PointsMaterial({
            size: 0.8,
            color: 0x10b981,
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
        if(!currentMesh || !nextMesh) return;
        
        gsap.to(currentMesh.material, { opacity: 0, duration: 1.5, ease: "power2.inOut" });
        gsap.to(currentMesh.position, { z: -50, duration: 1.5, ease: "power2.inOut" });
        
        nextMesh.position.z = -50;
        gsap.to(nextMesh.material, { opacity: 1, duration: 1.5, ease: "power2.inOut" });
        gsap.to(nextMesh.position, { z: 0, duration: 1.5, ease: "power2.inOut" });
        
        this.currentIndex = newIndex;
        
        document.querySelectorAll('.tour-slide-btn').forEach((btn, idx) => {
            if(idx === newIndex) btn.classList.add('active-slide');
            else btn.classList.remove('active-slide');
        });
    }

    initGSAP() {
        const tl = gsap.timeline();
        // Removed fade-in delays to ensure text displays instantly on all devices
          
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
        this.mouse.lerp(this.targetMouse, 0.05);

        this.meshes.forEach((mesh, index) => {
            const positionAttribute = mesh.geometry.attributes.position;
            const vertex = new THREE.Vector3();
            for (let i = 0; i < positionAttribute.count; i++) {
                vertex.fromBufferAttribute(positionAttribute, i);
                vertex.z = Math.sin(vertex.x * 0.05 + this.time + index) * 3 + Math.cos(vertex.y * 0.05 + this.time) * 3;
                positionAttribute.setXYZ(i, vertex.x, vertex.y, vertex.z);
            }
            positionAttribute.needsUpdate = true;
            
            if(index === this.currentIndex) {
                mesh.rotation.y = this.mouse.x * 0.1;
                mesh.rotation.x = -this.mouse.y * 0.1;
            }
        });

        if(this.particles) {
            this.particles.rotation.y = this.time * 0.05;
            this.particles.rotation.x = this.time * 0.02;
            this.particles.position.x = -this.mouse.x * 20;
            this.particles.position.y = -this.mouse.y * 20;
        }
        
        if(this.dirLight) {
            this.dirLight.position.x = this.mouse.x * 100;
            this.dirLight.position.y = this.mouse.y * 100 + 50;
        }

        this.renderer.render(this.scene, this.camera);
        requestAnimationFrame(this.render.bind(this));
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new Home3D();
});
</script>
