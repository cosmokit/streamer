import { sidebarItems, steps, progress, username } from "./mockData";

export const CosmicStream = () => (
  <div className="w-full h-full flex relative overflow-hidden" style={{ background: "linear-gradient(135deg, #1a0533 0%, #0d0a2e 50%, #160830 100%)", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Light rays */}
    <div className="absolute inset-0 pointer-events-none" style={{
      background: "linear-gradient(125deg, transparent 30%, #8b5cf622 50%, transparent 70%)"
    }} />
    {/* Particles dots */}
    {[...Array(12)].map((_, i) => (
      <div key={i} className="absolute rounded-full pointer-events-none" style={{
        width: 2 + (i % 3),
        height: 2 + (i % 3),
        top: `${10 + (i * 7) % 80}%`,
        left: `${5 + (i * 13) % 90}%`,
        background: i % 2 ? "#a78bfa" : "#7c3aed",
        opacity: 0.3 + (i % 4) * 0.15,
      }} />
    ))}
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1 relative z-10" style={{ background: "#0d0a2e88", borderRight: "1px solid #8b5cf633", backdropFilter: "blur(8px)" }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{ color: "#a78bfa" }}>
        🔮 COSMIC<span style={{ color: "#c4b5fd" }}>STREAM</span>
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 rounded-md text-[8px]"
          style={{
            background: i === 0 ? "#8b5cf622" : "transparent",
            color: i === 0 ? "#c4b5fd" : "#6d5a9e",
            borderLeft: i === 0 ? "2px solid #8b5cf6" : "2px solid transparent",
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden relative z-10">
      <div className="text-[9px] mb-1" style={{ color: "#a78bfa88" }}>Привет, {username} ✨</div>
      <div className="text-[13px] font-bold mb-3" style={{ color: "#c4b5fd" }}>
        Мой прогресс
      </div>
      <div className="w-full h-2 rounded-full mb-4" style={{ background: "#1a1040" }}>
        <div className="h-full rounded-full" style={{
          width: `${progress}%`,
          background: "linear-gradient(90deg, #7c3aed, #a78bfa, #c4b5fd)",
          boxShadow: "0 0 12px #8b5cf644",
        }} />
      </div>
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => (
          <div
            key={i}
            className="flex items-center gap-2 px-2 py-1.5 rounded-lg text-[8px]"
            style={{
              background: step.done ? "#8b5cf611" : "#0d0a2e66",
              border: `1px solid ${step.done ? "#8b5cf633" : "#ffffff06"}`,
              color: step.done ? "#c4b5fd" : "#5a4880",
              backdropFilter: "blur(4px)",
            }}
          >
            <span style={{ color: step.done ? "#a78bfa" : "#3a2860" }}>{step.done ? "★" : "☆"}</span>
            <span>{step.title}</span>
          </div>
        ))}
      </div>
    </div>
  </div>
);
