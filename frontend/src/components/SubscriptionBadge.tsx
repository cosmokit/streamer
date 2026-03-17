interface SubscriptionBadgeProps {
  tier: "lite" | "pro";
  size?: "sm" | "md";
}

const SubscriptionBadge = ({ tier, size = "sm" }: SubscriptionBadgeProps) => {
  const isSmall = size === "sm";
  const px = isSmall ? "px-2 py-0.5" : "px-2.5 py-1";
  const text = isSmall ? "text-[10px]" : "text-[11px]";

  if (tier === "pro") {
    return (
      <span
        className={`${px} ${text} rounded-md font-bold inline-block`}
        style={{
          background: "linear-gradient(135deg, hsl(270 75% 55% / 0.2), hsl(270 75% 45% / 0.3))",
          color: "hsl(270 75% 75%)",
          border: "1px solid hsl(270 75% 55% / 0.3)",
          textShadow: "0 0 8px hsl(270 75% 55% / 0.5)",
        }}
      >
        ✦ PRO
      </span>
    );
  }

  return (
    <span
      className={`${px} ${text} rounded-md font-bold inline-block`}
      style={{
        background: "linear-gradient(135deg, hsl(90 85% 45% / 0.12), hsl(90 85% 35% / 0.2))",
        color: "hsl(90 85% 60%)",
        border: "1px solid hsl(90 85% 45% / 0.25)",
      }}
    >
      LITE
    </span>
  );
};

export default SubscriptionBadge;
