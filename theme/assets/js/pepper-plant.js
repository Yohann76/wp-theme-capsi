/**
 * Pepper Plant 3D — Three.js procedural scene
 * Auto-rotation + mouse parallax
 */
import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.168.0/build/three.module.min.js';

( function () {

  const canvas = document.getElementById( 'pepper-plant-canvas' );
  if ( ! canvas ) return;

  // ── Renderer ──────────────────────────────────────────────────
  const renderer = new THREE.WebGLRenderer( { canvas, alpha: true, antialias: true } );
  renderer.setPixelRatio( Math.min( window.devicePixelRatio, 2 ) );
  renderer.shadowMap.enabled  = true;
  renderer.shadowMap.type     = THREE.PCFSoftShadowMap;
  renderer.toneMapping        = THREE.ACESFilmicToneMapping;
  renderer.toneMappingExposure = 1.1;

  // ── Scene + Camera ────────────────────────────────────────────
  const scene  = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera( 42, 1, 0.1, 100 );
  camera.position.set( 0, 0.1, 7.2 );
  camera.lookAt( 0, 0.1, 0 );

  // ── Lights ────────────────────────────────────────────────────
  scene.add( new THREE.AmbientLight( 0xfff8f0, 1.0 ) );

  const sun = new THREE.DirectionalLight( 0xfffaf0, 2.2 );
  sun.position.set( 3, 6, 4 );
  sun.castShadow = true;
  sun.shadow.mapSize.set( 1024, 1024 );
  scene.add( sun );

  const fill = new THREE.PointLight( 0x4a8a5e, 1.0, 25 );
  fill.position.set( -3.5, 1.5, 2 );
  scene.add( fill );

  const rim = new THREE.DirectionalLight( 0x3a6a4e, 0.5 );
  rim.position.set( -2, 3, -4 );
  scene.add( rim );

  // ── Materials ─────────────────────────────────────────────────
  const mStem = new THREE.MeshStandardMaterial( { color: 0x3d5e30, roughness: 0.78 } );
  const mLeaf = new THREE.MeshStandardMaterial( { color: 0x4a7d3e, roughness: 0.85, side: THREE.DoubleSide } );
  const mLeafD= new THREE.MeshStandardMaterial( { color: 0x2d5a2a, roughness: 0.90, side: THREE.DoubleSide } );
  const mRed  = new THREE.MeshStandardMaterial( { color: 0xc82020, roughness: 0.22, metalness: 0.04 } );
  const mOrng = new THREE.MeshStandardMaterial( { color: 0xd96518, roughness: 0.22, metalness: 0.04 } );
  const mCalx = new THREE.MeshStandardMaterial( { color: 0x3a5a28, roughness: 0.85, side: THREE.DoubleSide } );
  const mPot  = new THREE.MeshStandardMaterial( { color: 0x8a4e2a, roughness: 0.82 } );
  const mPotD = new THREE.MeshStandardMaterial( { color: 0x6e3a1e, roughness: 0.85 } );
  const mSoil = new THREE.MeshStandardMaterial( { color: 0x2e1e0e, roughness: 1.00 } );

  // ── Helpers ───────────────────────────────────────────────────

  function tube( pts, r, seg = 12 ) {
    const curve = new THREE.CatmullRomCurve3( pts );
    const m = new THREE.Mesh( new THREE.TubeGeometry( curve, seg, r, 8 ), mStem );
    m.castShadow = true;
    return m;
  }

  function leaf( scale = 1, mat = mLeaf ) {
    const s  = new THREE.Shape();
    s.moveTo( 0, 0 );
    s.bezierCurveTo(  0.28 * scale, 0.18 * scale,  0.40 * scale, 0.58 * scale, 0, 1.15 * scale );
    s.bezierCurveTo( -0.40 * scale, 0.58 * scale, -0.28 * scale, 0.18 * scale, 0, 0 );
    const m = new THREE.Mesh( new THREE.ShapeGeometry( s, 10 ), mat );
    m.castShadow = true;
    return m;
  }

  function pepper( mat = mRed ) {
    const g = new THREE.Group();

    // Body via lathe profile
    const pts = [
      new THREE.Vector2( 0.001, 0.00 ),
      new THREE.Vector2( 0.085, 0.09 ),
      new THREE.Vector2( 0.130, 0.24 ),
      new THREE.Vector2( 0.140, 0.44 ),
      new THREE.Vector2( 0.130, 0.62 ),
      new THREE.Vector2( 0.095, 0.78 ),
      new THREE.Vector2( 0.050, 0.92 ),
      new THREE.Vector2( 0.012, 0.99 ),
      new THREE.Vector2( 0.001, 1.00 ),
    ];
    const body = new THREE.Mesh( new THREE.LatheGeometry( pts, 20 ), mat );
    body.castShadow = true;
    g.add( body );

    // Peduncle
    const ped = new THREE.Mesh( new THREE.CylinderGeometry( 0.014, 0.024, 0.28, 7 ), mStem );
    ped.position.y = 1.10;
    g.add( ped );

    // Calyx petals
    for ( let i = 0; i < 5; i++ ) {
      const angle = ( i / 5 ) * Math.PI * 2;
      const petal = leaf( 0.12, mCalx );
      petal.position.set( Math.cos( angle ) * 0.06, 1.02, Math.sin( angle ) * 0.06 );
      petal.rotation.set( Math.sin( angle ) * 0.6 - 0.2, angle, Math.cos( angle ) * 0.4 );
      g.add( petal );
    }
    return g;
  }

  // ── Plant group ───────────────────────────────────────────────
  const plant = new THREE.Group();
  scene.add( plant );

  // Main stem (organic S-curve)
  plant.add( tube( [
    new THREE.Vector3(  0.00, -2.05,  0.00 ),
    new THREE.Vector3(  0.09, -1.30,  0.07 ),
    new THREE.Vector3( -0.07, -0.40, -0.09 ),
    new THREE.Vector3(  0.05,  0.50,  0.08 ),
    new THREE.Vector3( -0.04,  1.40, -0.05 ),
    new THREE.Vector3(  0.02,  2.05,  0.03 ),
  ], 0.058, 18 ) );

  // ── Branch factory ────────────────────────────────────────────
  function branch( originY, dx, dz, len, pMat ) {
    const ox = dx * 0.05, oz = dz * 0.05;
    plant.add( tube( [
      new THREE.Vector3( ox,              originY,         oz              ),
      new THREE.Vector3( ox + dx*len*0.4, originY+len*0.2, oz + dz*len*0.4 ),
      new THREE.Vector3( ox + dx*len,     originY+len*0.1, oz + dz*len     ),
    ], 0.033, 9 ) );

    const tx = ox + dx * len;
    const ty = originY + len * 0.1;
    const tz = oz + dz * len;

    // Hanging pepper
    const p = pepper( pMat );
    p.scale.setScalar( 0.54 );
    p.rotation.x = Math.PI;
    p.position.set( tx, ty - 0.33, tz );
    plant.add( p );

    // 2 leaves per branch
    const angle = Math.atan2( dx, dz );
    [ [ 0.45, 0.45, mLeaf,  -0.3, 0,  dx > 0 ? -0.45 :  0.45 ],
      [ 0.38, 0.75, mLeafD, -0.5, 0.6, dx > 0 ?  0.30 : -0.30 ],
    ].forEach( ( [ sc, t, mat, rx, ry, rz ] ) => {
      const l = leaf( sc, mat );
      l.position.set(
        ox + dx * len * t,
        originY + len * 0.15,
        oz + dz * len * t + 0.06
      );
      l.rotation.set( rx, angle + ry, rz );
      plant.add( l );
    } );
  }

  branch( -0.85,  1.00,  0.30, 0.88, mRed  );
  branch( -0.15, -0.90, -0.20, 0.78, mOrng );
  branch(  0.50,  0.80, -0.50, 0.82, mRed  );
  branch(  1.10, -0.70,  0.40, 0.72, mRed  );

  // Upper solo leaves
  [
    [ 0.55, mLeaf,  [  0.16, 1.82,  0.10 ], [ -0.18, 0.30, -0.50 ] ],
    [ 0.48, mLeafD, [ -0.19, 1.94, -0.06 ], [ -0.28, -0.40, 0.40 ] ],
    [ 0.42, mLeaf,  [  0.06, 2.06,  0.13 ], [ -0.12, 0.65, -0.22 ] ],
  ].forEach( ( [ sc, mat, pos, rot ] ) => {
    const l = leaf( sc, mat );
    l.position.set( ...pos );
    l.rotation.set( ...rot );
    plant.add( l );
  } );

  // ── Pot ───────────────────────────────────────────────────────
  const potG = new THREE.Group();

  // Body (lathe)
  const potPts = [
    new THREE.Vector2( 0.48, 0.00 ),
    new THREE.Vector2( 0.50, 0.28 ),
    new THREE.Vector2( 0.54, 0.58 ),
    new THREE.Vector2( 0.60, 0.84 ),
    new THREE.Vector2( 0.65, 0.95 ),
    new THREE.Vector2( 0.67, 1.00 ),
  ];
  const potBody = new THREE.Mesh( new THREE.LatheGeometry( potPts, 28 ), mPot );
  potBody.castShadow  = true;
  potBody.receiveShadow = true;
  potG.add( potBody );

  // Rim torus
  const potRim = new THREE.Mesh( new THREE.TorusGeometry( 0.67, 0.038, 8, 28 ), mPotD );
  potRim.position.y = 1.01;
  potG.add( potRim );

  // Soil disc
  const soilDisc = new THREE.Mesh( new THREE.CircleGeometry( 0.64, 28 ), mSoil );
  soilDisc.rotation.x = -Math.PI / 2;
  soilDisc.position.y  =  0.98;
  potG.add( soilDisc );

  // Bottom cap
  const potBase = new THREE.Mesh( new THREE.CircleGeometry( 0.48, 28 ), mPotD );
  potBase.rotation.x = Math.PI / 2;
  potBase.position.y  = 0.005;
  potG.add( potBase );

  potG.position.y = -2.58;
  plant.add( potG );

  // ── Mouse interaction ─────────────────────────────────────────
  let targetX = 0, targetY = 0;
  let currentX = 0, currentY = 0;
  let lastMouse = 0;

  const hero = document.querySelector( '.home-hero' );
  if ( hero ) {
    hero.addEventListener( 'mousemove', ( e ) => {
      const r = hero.getBoundingClientRect();
      targetX = ( ( e.clientY - r.top  ) / r.height - 0.5 ) * -0.45;
      targetY = ( ( e.clientX - r.left ) / r.width  - 0.5 ) *  0.65;
      lastMouse = Date.now();
    } );
    hero.addEventListener( 'mouseleave', () => { targetX = 0; targetY = 0; } );
  }

  // ── Resize ────────────────────────────────────────────────────
  function resize() {
    const w = canvas.clientWidth;
    const h = canvas.clientHeight;
    if ( canvas.width === w && canvas.height === h ) return;
    renderer.setSize( w, h, false );
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  }

  // ── Animate ───────────────────────────────────────────────────
  const clock = new THREE.Clock();
  let autoY   = 0;

  function animate() {
    requestAnimationFrame( animate );
    resize();

    const t     = clock.getElapsedTime();
    autoY      += 0.0038;

    const fade  = Math.max( 0, 1 - ( Date.now() - lastMouse ) / 3000 );
    const idleX = Math.sin( t * 0.38 ) * 0.035;

    currentX += ( ( targetX * fade + idleX * ( 1 - fade ) ) - currentX ) * 0.055;
    currentY += ( ( targetY * fade                         ) - currentY ) * 0.055;

    plant.rotation.x = currentX;
    plant.rotation.y = autoY + currentY;
    plant.rotation.z = Math.cos( t * 0.28 ) * 0.018;

    // Soft fill-light drift
    fill.position.x = Math.sin( t * 0.45 ) * 3.0;
    fill.position.z = Math.cos( t * 0.45 ) * 1.5 + 2;

    renderer.render( scene, camera );
  }

  animate();

} )();
