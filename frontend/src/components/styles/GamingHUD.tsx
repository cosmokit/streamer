import { sidebarItems, steps, progress, username } from "./mockData";

export const GamingHUD = () => (
  <div className="w-full h-full flex" style={{ background: "#030d08", fontFamily: "'Segoe UI', sans-serif" }}>
    {/* Sidebar */}
    <div className="w-[28%] h-full p-3 flex flex-col gap-1" style={{ background: "#021a0a88", borderRight: "1px solid #00ff4433" }}>
      <div className="text-[10px] font-bold mb-2 px-2" style={{ color: "#00ff44" }}>
        ◆ HUD<span style={{ color: "#44ffaa" }}>PANEL</span>
      </div>
      {sidebarItems.map((item, i) => (
        <div
          key={i}
          className="flex items-center gap-1.5 px-2 py-1 text-[8px]"
          style={{
            background: i === 0 ? "#00ff4415" : "transparent",
            color: i === 0 ? "#00ff44" : "#336644",
            borderLeft: i === 0 ? "2px solid #00ff44" : "2px solid transparent",
            clipPath: i === 0 ? "polygon(0 0, 95% 0, 100% 30%, 100% 100%, 0 100%)" : undefined,
          }}
        >
          <span>{item.icon}</span>
          <span>{item.label}</span>
        </div>
      ))}
    </div>
    {/* Main */}
    <div className="flex-1 p-4 overflow-hidden relative">
      {/* Scanline effect */}
      <div className="absolute inset-0 pointer-events-none opacity-5" style={{
        background: "repeating-linear-gradient(0deg, transparent, transparent 2px, #00ff44 2px, #00ff44 3px)"
      }} />
      <div className="text-[9px] mb-1" style={{ color: "#00ff4466" }}>OPERATOR: {username}</div>
      <div className="text-[13px] font-bold mb-3" style={{ color: "#00ff44", letterSpacing: "2px" }}>
        ▸ ПРОГРЕСС МИССИИ
      </div>
      <div className="w-full h-2 mb-4" style={{ background: "#0a1a0d", border: "1px solid #00ff4433" }}>
        <div className="h-full" style={{ width: `${progress}%`, background: "#00ff44", boxShadow: "0 0 8px #00ff4466" }} />
      </div>
      <div className="flex flex-col gap-1.5">
        {steps.map((step, i) => (
          <div
            key={i}
            className="flex items-center gap-2 px-2 py-1.5 text-[8px]"
            style={{
              background: step.done ? "#00ff4408" : "#030d0800",
              border: `1px solid ${step.done ? "#00ff4433" : "#ffffff06"}`,
              color: step.done ? "#00ff44" : "#225533",
              clipPath: "polygon(0 0, 97% 0, 100% 40%, 100% 100%, 3% 100%, 0 60%)",
            }}
          >
            <span>{step.done ? "◆" : "◇"}</span>
            <span>{step.title}</span>
          </div>
        ))}
      </div>
    </div>
  </div>
);
