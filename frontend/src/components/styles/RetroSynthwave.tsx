import { sidebarItems, steps, progress, username } from "./mockData";

export const RetroSynthwave = () => (
  <div className="w-full h-full flex relative overflow-hidden" style={{ background: "linear-gradient(180deg, #0d0221 0%, #1a0533 40%, #2d1b69 100%)", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Sun */}
    <div className="absolute pointer-events-none" style={{
      width: 80, height: 40, bottom: "15%", left: "60%",
      background: "linear-gradient(180deg, #ff6ec7, #ff9a00)",
      borderRadius: "80px 80px 0 0",
      opacity: 0.4,
    }} />
    {/* Grid */}
    <div className="absolute pointer-events-none" style={{
      bottom: 0, left: 0, right: 0, height: "30%",
      background: "linear-gradient(180deg, transparent, #ff6ec711)",
      backgroundImage: `
        linear-gradient(90deg, #ff6ec715 1px, transparent 1px),
        linear-gradient(0deg, #ff6ec715 1px, transparent 1px)
      `,
      backgroundSize: "20px 20px",
      transform: "perspective(200px) rotateX(40deg)",
      transformOrigin: "bottom",
    }} />
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1 relative z-10" style={{ background: "#0d022188", borderRight: "1px solid #ff6ec733" }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{ color: "#ff6ec7" }}>
        🌃 SYNTH<span style={{ color: "#00d4ff" }}>WAVE</span>
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 rounded text-[8px]"
          style={{
            background: i === 0 ? "#ff6ec722" : "transparent",
            color: i === 0 ? "#ff6ec7" : "#8855aa",
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden relative z-10">
      <div className="text-[9px] mb-1" style={{ color: "#00d4ff88" }}>Привет, {username}</div>
      <div className="text-[13px] font-bold mb-3" style={{ color: "#ff6ec7" }}>
        Мой прогресс
      </div>
      <div className="w-full h-2 rounded-full mb-4" style={{ background: "#1a0533" }}>
        <div className="h-full rounded-full" style={{
          width: `${progress}%`,
          background: "linear-gradient(90deg, #ff6ec7, #00d4ff)",
          boxShadow: "0 0 8px #ff6ec744",
        }} />
      </div>
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => (
          <div
            key={i}
            className="flex items-center gap-2 px-2 py-1.5 rounded text-[8px]"
            style={{
              background: step.done ? "#ff6ec70a" : "#0d022188",
              border: `1px solid ${step.done ? "#ff6ec733" : "#ffffff06"}`,
              color: step.done ? "#ff6ec7" : "#553377",
            }}
          >
            <span style={{ color: step.done ? "#00d4ff" : "#2a1545" }}>{step.done ? "▸" : "▹"}</span>
            <span>{step.title}</span>
          </div>
        ))}
      </div>
    </div>
  </div>
);
